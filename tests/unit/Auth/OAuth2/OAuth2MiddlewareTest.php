<?php
/**
 * This file is part of graze/gigya-client
 *
 * Copyright (c) 2016 Nature Delivered Ltd. <https://www.graze.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://github.com/graze/gigya-client/blob/master/LICENSE.md
 * @link    https://github.com/graze/gigya-client
 */

namespace Graze\Gigya\Test\Unit\Auth\OAuth2;

use Graze\Gigya\Auth\OAuth2\AccessToken;
use Graze\Gigya\Auth\OAuth2\GrantInterface;
use Graze\Gigya\Auth\OAuth2\OAuth2Middleware;
use Graze\Gigya\Test\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery as m;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class OAuth2MiddlewareTest extends TestCase
{
    /** @var mixed */
    private $grant;

    public function setUp()
    {
        $this->grant = m::mock(GrantInterface::class);
    }

    public function testSign()
    {
        $h = new MockHandler([
            function (RequestInterface $request) {
                $this->assertEquals('OAuth test', $request->getHeaderLine('Authorization'));
                return new Response(200);
            },
        ]);

        $token = new AccessToken('test');
        $this->grant->shouldReceive('getToken')
                    ->andReturn($token);

        $stack = new HandlerStack($h);
        $stack->push(OAuth2Middleware::middleware($this->grant));

        $comp = $stack->resolve();

        $p = $comp(new Request('GET', 'https://example.com'), ['auth' => 'gigya-oauth2']);
        $this->assertInstanceOf(PromiseInterface::class, $p);
    }

    public function testErrorThatIsNot401()
    {
        $h = new MockHandler([
            function (RequestInterface $request) {
                $this->assertEquals('OAuth test', $request->getHeaderLine('Authorization'));
                return new Response(503);
            },
        ]);

        $token = new AccessToken('test');
        $this->grant->shouldReceive('getToken')
                    ->andReturn($token);

        $stack = new HandlerStack($h);
        $stack->push(OAuth2Middleware::middleware($this->grant));

        $comp = $stack->resolve();

        $p = $comp(new Request('GET', 'https://example.com'), ['auth' => 'gigya-oauth2']);
        $this->assertInstanceOf(PromiseInterface::class, $p);
    }

    public function testErrorThatIsNotRetried()
    {
        $h = new MockHandler([
            function (RequestInterface $request, array $options) {
                $this->assertEquals('OAuth test', $request->getHeaderLine('Authorization'));
                $this->assertEquals(1, $options['retries']);
                return new Response(401);
            },
        ]);

        $token = new AccessToken('test');
        $this->grant->shouldReceive('getToken')
                    ->andReturn($token);

        $stack = new HandlerStack($h);
        $stack->push(OAuth2Middleware::middleware($this->grant));

        $comp = $stack->resolve();

        /** @var PromiseInterface $p */
        $p = $comp(new Request('GET', 'https://example.com'), ['auth' => 'gigya-oauth2', 'retries' => 1]);
        $this->assertInstanceOf(PromiseInterface::class, $p);
        /** @var ResponseInterface $response */
        $response = $p->wait();
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testErrorThatIsRetried()
    {
        $h = new MockHandler([
            function (RequestInterface $request, array $options) {
                $this->assertEquals('OAuth test', $request->getHeaderLine('Authorization'));
                if (isset($options['retries'])) {
                    $this->assertEquals(0, $options['retries']);
                }
                return new Response(401);
            },
            function (RequestInterface $request, array $options) {
                $this->assertEquals('OAuth test2', $request->getHeaderLine('Authorization'));
                $this->assertEquals(1, $options['retries']);
                return new Response(200);
            },
        ]);

        $token1 = new AccessToken('test');
        $token2 = new AccessToken('test2');
        $this->grant->shouldReceive('getToken')
                    ->andReturn($token1, $token2);

        $stack = new HandlerStack($h);
        $stack->push(OAuth2Middleware::middleware($this->grant));

        $comp = $stack->resolve();

        /** @var PromiseInterface $p */
        $p = $comp(new Request('GET', 'https://example.com'), ['auth' => 'gigya-oauth2']);
        $this->assertInstanceOf(PromiseInterface::class, $p);
        /** @var ResponseInterface $response */
        $response = $p->wait();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testErrorThatIsNotOauthAuth()
    {
        $h = new MockHandler([
            function (RequestInterface $request) {
                $this->assertEquals('', $request->getHeaderLine('Authorization'));
                return new Response(401);
            },
        ]);

        $stack = new HandlerStack($h);
        $stack->push(OAuth2Middleware::middleware($this->grant));

        $comp = $stack->resolve();

        /** @var PromiseInterface $p */
        $p = $comp(new Request('GET', 'https://example.com'), ['auth' => 'none']);
        $this->assertInstanceOf(PromiseInterface::class, $p);
        /** @var ResponseInterface $response */
        $response = $p->wait(true);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testErrorWhenNoTokenIsReturnedWillNotIntercept()
    {
        $h = new MockHandler([
            function (RequestInterface $request) {
                $this->assertEquals('', $request->getHeaderLine('Authorization'));
                return new Response(401);
            },
        ]);

        $this->grant->shouldReceive('getToken')
                    ->atLeast()
                    ->once()
                    ->andReturn(null);

        $stack = new HandlerStack($h);
        $stack->push(OAuth2Middleware::middleware($this->grant));

        $comp = $stack->resolve();

        /** @var PromiseInterface $p */
        $p = $comp(new Request('GET', 'https://example.com'), ['auth' => 'gigya-oauth2']);
        $this->assertInstanceOf(PromiseInterface::class, $p);
        /** @var ResponseInterface $response */
        $response = $p->wait(true);
        $this->assertEquals(401, $response->getStatusCode());
    }
}
