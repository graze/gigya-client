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

use Graze\Gigya\Auth\HttpsAuthMiddleware;
use Graze\Gigya\Test\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class HttpsAuthMiddlewareTest extends TestCase
{
    public function testKeyAndSecretIsPassedToParams()
    {
        $handler = new MockHandler(
            [
                function (RequestInterface $request) {
                    $params = \GuzzleHttp\Psr7\parse_query($request->getBody());
                    $this->assertArrayHasKey('apiKey', $params);
                    $this->assertEquals('key', $params['apiKey']);
                    $this->assertArrayHasKey('secret', $params);
                    $this->assertEquals('secret', $params['secret']);
                    return new Response(200);
                },
            ]
        );

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'https://example.com'), ['auth' => 'gigya']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testKeySecretAndUserKeyIsPassedToParams()
    {
        $handler = new MockHandler(
            [
                function (RequestInterface $request) {
                    $params = \GuzzleHttp\Psr7\parse_query($request->getBody());
                    $this->assertArrayHasKey('apiKey', $params);
                    $this->assertEquals('key', $params['apiKey']);
                    $this->assertArrayHasKey('secret', $params);
                    $this->assertEquals('secret', $params['secret']);
                    $this->assertArrayHasKey('userKey', $params);
                    $this->assertEquals('user', $params['userKey']);
                    return new Response(200);
                },
            ]
        );

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'https://example.com'), ['auth' => 'gigya']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testAccessors()
    {
        $auth = new HttpsAuthMiddleware(
            function () {
            },
            'key',
            'secret',
            'user'
        );
        static::assertEquals('key', $auth->getApiKey());
        static::assertEquals('secret', $auth->getSecret());
        static::assertEquals('user', $auth->getUserKey());
    }

    public function testSubscriberDoesNotDoAnythingForNonHttpsRequests()
    {
        $handler = new MockHandler(
            [
                function (RequestInterface $request) {
                    $params = \GuzzleHttp\Psr7\parse_query($request->getBody());
                    $this->assertArrayNotHasKey('apiKey', $params);
                    $this->assertArrayNotHasKey('secret', $params);
                    $this->assertArrayNotHasKey('userKey', $params);
                    return new Response(200);
                },
            ]
        );

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'http://example.com'), ['auth' => 'gigya']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testSubscriberDoesNotDoAnythingForNonGigyaAuthRequests()
    {
        $handler = new MockHandler(
            [
                function (RequestInterface $request, array $options) {
                    $params = \GuzzleHttp\Psr7\parse_query($request->getBody());
                    $this->assertArrayNotHasKey('apiKey', $params);
                    $this->assertArrayNotHasKey('secret', $params);
                    $this->assertArrayNotHasKey('userKey', $params);
                    return new Response(200);
                },
            ]
        );

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'http://example.com'), ['auth' => 'oauth']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }
}
