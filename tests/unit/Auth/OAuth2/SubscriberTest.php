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
use Graze\Gigya\Auth\OAuth2\Subscriber;
use Graze\Gigya\Test\TestCase;
use GuzzleHttp\Collection;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Mockery as m;

class SubscriberTest extends TestCase
{
    /** @var mixed */
    private $grant;
    /** @var Subscriber */
    private $subscriber;

    public function setUp()
    {
        $this->grant = m::mock(GrantInterface::class);
        $this->subscriber = new Subscriber($this->grant);
    }

    public function testInstanceOf()
    {
        static::assertInstanceOf(SubscriberInterface::class, $this->subscriber);
    }

    public function testGetEvents()
    {
        static::assertEquals([
            'before' => ['sign', RequestEvents::SIGN_REQUEST],
            'error'  => ['error', RequestEvents::EARLY],
        ], $this->subscriber->getEvents());
    }

    public function testSign()
    {
        $event = m::mock(BeforeEvent::class);
        $request = m::mock(RequestInterface::class);
        $event->shouldReceive('getRequest')
              ->andReturn($request);
        $request->shouldReceive('getScheme')
                ->andReturn('https');
        $request->shouldReceive('getConfig->get')
                ->with('auth')
                ->andReturn('gigya-oauth2');

        $token = new AccessToken('test');

        $this->grant->shouldReceive('getToken')
                    ->andReturn($token);

        $request->shouldReceive('addHeader')
                ->with('Authorization', 'OAuth test')
                ->once();

        $this->subscriber->sign($event);
    }

    public function testErrorThatIsNot401()
    {
        $event = m::mock(ErrorEvent::class);
        $response = m::mock(ResponseInterface::class);
        $event->shouldReceive('getResponse')
              ->andReturn($response);
        $response->shouldReceive('getStatusCode')
                 ->andReturn(503);
        $this->subscriber->error($event);
    }

    public function testErrorThatIsNotRetried()
    {
        $event = m::mock(ErrorEvent::class);

        $response = m::mock(ResponseInterface::class);
        $event->shouldReceive('getResponse')
              ->atLeast()
              ->once()
              ->andReturn($response);
        $response->shouldReceive('getStatusCode')
                 ->atLeast()
                 ->once()
                 ->andReturn(401);

        $request = m::mock(RequestInterface::class);
        $event->shouldReceive('getRequest')
              ->atLeast()
              ->once()
              ->andReturn($request);
        $request->shouldReceive('getScheme')
                ->atLeast()
                ->once()
                ->andReturn('https');
        $config = m::mock(Collection::class);
        $request->shouldReceive('getConfig')
                ->atLeast()
                ->once()
                ->andReturn($config);
        $config->shouldReceive('get')
               ->with('auth')
               ->atLeast()
               ->once()
               ->andReturn('gigya-oauth2');
        $config->shouldReceive('get')
               ->with('retried')
               ->atLeast()
               ->once()
               ->andReturn(false);

        $token = new AccessToken('test2');
        $this->grant->shouldReceive('getToken')
                    ->andReturn($token);

        $config->shouldReceive('set')
               ->with('retried', true)
               ->atLeast()
               ->once();

        $newResponse = m::mock(ResponseInterface::class);

        $event->shouldReceive('getClient->send')
              ->with($request)
              ->atLeast()
              ->once()
              ->andReturn($newResponse);
        $event->shouldReceive('intercept')
              ->atLeast()
              ->once()
              ->with($newResponse);

        $this->subscriber->error($event);
    }

    public function testErrorThatIsNotOauthAuth()
    {
        $event = m::mock(ErrorEvent::class);

        $response = m::mock(ResponseInterface::class);
        $event->shouldReceive('getResponse')
              ->atLeast()
              ->once()
              ->andReturn($response);
        $response->shouldReceive('getStatusCode')
                 ->atLeast()
                 ->once()
                 ->andReturn(401);

        $request = m::mock(RequestInterface::class);
        $event->shouldReceive('getRequest')
              ->atLeast()
              ->once()
              ->andReturn($request);
        $request->shouldReceive('getScheme')
                ->atLeast()
                ->once()
                ->andReturn('https');
        $request->shouldReceive('getConfig->get')
                ->with('auth')
                ->atLeast()
                ->once()
                ->andReturn('none');

        $this->subscriber->error($event);
    }

    public function testErrorWhenNoTokenIsReturnedWillNotIntercept()
    {
        $event = m::mock(ErrorEvent::class);

        $response = m::mock(ResponseInterface::class);
        $event->shouldReceive('getResponse')
              ->atLeast()
              ->once()
              ->andReturn($response);
        $response->shouldReceive('getStatusCode')
                 ->atLeast()
                 ->once()
                 ->andReturn(401);

        $request = m::mock(RequestInterface::class);
        $event->shouldReceive('getRequest')
              ->atLeast()
              ->once()
              ->andReturn($request);
        $request->shouldReceive('getScheme')
                ->atLeast()
                ->once()
                ->andReturn('https');
        $config = m::mock(Collection::class);
        $request->shouldReceive('getConfig')
                ->atLeast()
                ->once()
                ->andReturn($config);
        $config->shouldReceive('get')
               ->with('auth')
               ->atLeast()
               ->once()
               ->andReturn('gigya-oauth2');
        $config->shouldReceive('get')
               ->with('retried')
               ->atLeast()
               ->once()
               ->andReturn(false);

        $this->grant->shouldReceive('getToken')
                    ->andReturn(null);

        $this->subscriber->error($event);
    }
}
