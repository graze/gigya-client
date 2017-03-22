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
use Mockery as m;
use Psr\Http\Message\RequestInterface;

class HttpsAuthMiddlewareTest extends TestCase
{
    public function testKeyAndSecretIsPassedToParams()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                $query = $request->getUri()->getQuery();
                $this->assertRegExp('/apiKey=key/', $query);
                $this->assertRegExp('/secret=secret/', $query);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'https://example.com'), ['auth' => 'gigya']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testKeySecretAndUserKeyIsPassedToParams()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                $query = $request->getUri()->getQuery();
                $this->assertRegExp('/apiKey=key/', $query);
                $this->assertRegExp('/secret=secret/', $query);
                $this->assertRegExp('/userKey=user/', $query);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'https://example.com'), ['auth' => 'gigya']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testAccessors()
    {
        $auth = new HttpsAuthMiddleware(function () {
        }, 'key', 'secret', 'user');
        static::assertEquals('key', $auth->getApiKey());
        static::assertEquals('secret', $auth->getSecret());
        static::assertEquals('user', $auth->getUserKey());
    }

    public function testSubscriberDoesNotDoAnythingForNonHttpsRequests()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                $query = $request->getUri()->getQuery();
                $this->assertNotRegExp('/apiKey=/', $query);
                $this->assertNotRegExp('/secret=/', $query);
                $this->assertNotRegExp('/userKey=/', $query);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'http://example.com'), ['auth' => 'gigya']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testSubscriberDoesNotDoAnythingForNonGigyaAuthRequests()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                $query = $request->getUri()->getQuery();
                $this->assertNotRegExp('/apiKey=/', $query);
                $this->assertNotRegExp('/secret=/', $query);
                $this->assertNotRegExp('/userKey=/', $query);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('GET', 'http://example.com'), ['auth' => 'oauth']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testKeyAndSecretIsPassedToParamsWithPOST()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                parse_str($request->getBody()->__toString(), $body);
                $this->assertEquals('key', $body['apiKey']);
                $this->assertEquals('secret', $body['secret']);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('POST', 'https://example.com'), ['auth' => 'gigya']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testKeySecretAndUserKeyIsPassedToParamsWithPOST()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                parse_str($request->getBody()->__toString(), $body);
                $this->assertEquals('key', $body['apiKey']);
                $this->assertEquals('secret', $body['secret']);
                $this->assertEquals('user', $body['userKey']);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('POST', 'https://example.com'), ['auth' => 'gigya']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testSubscriberDoesNotDoAnythingForNonHttpsRequestsWithPOST()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                parse_str($request->getBody()->__toString(), $body);
                $this->assertArrayNotHasKey('apiKey', $body);
                $this->assertArrayNotHasKey('secret', $body);
                $this->assertArrayNotHasKey('userKey', $body);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('POST', 'http://example.com'), ['auth' => 'gigya']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }

    public function testSubscriberDoesNotDoAnythingForNonGigyaAuthRequestsWithPOST()
    {
        $handler = new MockHandler([
            function (RequestInterface $request) {
                parse_str($request->getBody()->__toString(), $body);
                $this->assertArrayNotHasKey('apiKey', $body);
                $this->assertArrayNotHasKey('secret', $body);
                $this->assertArrayNotHasKey('userKey', $body);
                return new Response(200);
            },
        ]);

        $stack = new HandlerStack($handler);
        $stack->push(HttpsAuthMiddleware::middleware('key', 'secret', 'user'));

        $comp = $stack->resolve();

        $promise = $comp(new Request('POST', 'http://example.com'), ['auth' => 'oauth']);
        $this->assertInstanceOf(PromiseInterface::class, $promise);
    }
}
