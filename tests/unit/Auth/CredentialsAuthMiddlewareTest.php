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

namespace Graze\Gigya\Test\Unit\Auth;

use Graze\Gigya\Auth\CredentialsAuthMiddleware;
use Graze\Gigya\Test\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery as m;
use Psr\Http\Message\RequestInterface;

class CredentialsAuthMiddlewareTest extends TestCase
{
    public function testKeyAndSecretIsPassedToParams()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                $query = $request->getUri()->getQuery();
                $this->assertRegExp('/client_id=key/', $query);
                $this->assertRegExp('/client_secret=client_secret/', $query);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(CredentialsAuthMiddleware::middleware('key', 'client_secret'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'https://example.com'), ['auth' => 'credentials']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testKeySecretAndUserKeyIsPassedToParams()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                $query = $request->getUri()->getQuery();
                $this->assertRegExp('/client_id=user/', $query);
                $this->assertRegExp('/client_secret=client_secret/', $query);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(CredentialsAuthMiddleware::middleware('key', 'client_secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'https://example.com'), ['auth' => 'credentials']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testAccessors()
    {
        $auth = new CredentialsAuthMiddleware(function () {
        }, 'key', 'client_secret', 'user');
        static::assertEquals('key', $auth->getApiKey());
        static::assertEquals('client_secret', $auth->getSecret());
        static::assertEquals('user', $auth->getUserKey());
    }

    public function testSubscriberDoesNotDoAnythingForNonHttpsRequests()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                $query = $request->getUri()->getQuery();
                $this->assertNotRegExp('/client_id=/', $query);
                $this->assertNotRegExp('/client_secret=/', $query);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(CredentialsAuthMiddleware::middleware('key', 'secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'http://example.com'), ['auth' => 'credentials']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testSubscriberDoesNotDoAnythingForNonGigyaAuthRequests()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                $query = $request->getUri()->getQuery();
                $this->assertNotRegExp('/client_id=/', $query);
                $this->assertNotRegExp('/client_secret=/', $query);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(CredentialsAuthMiddleware::middleware('key', 'secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'https://example.com'), ['auth' => 'oauth']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }
}
