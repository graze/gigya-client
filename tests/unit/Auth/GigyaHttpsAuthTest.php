<?php

namespace Graze\Gigya\Test\Unit\Auth;

use Graze\Gigya\Auth\GigyaAuthInterface;
use Graze\Gigya\Auth\GigyaHttpsAuth;
use Graze\Gigya\Test\TestCase;
use GuzzleHttp\Collection;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Message\RequestInterface;
use Mockery as m;

class GigyaHttpsAuthTest extends TestCase
{
    public function testInstanceOf()
    {
        $auth = new GigyaHttpsAuth('key', 'secret');
        static::assertInstanceOf(GigyaAuthInterface::class, $auth);
        static::assertInstanceOf(SubscriberInterface::class, $auth);
    }

    public function testGetEventsHandlesBeforeAndSignsRequest()
    {
        $auth = new GigyaHttpsAuth('key', 'secret');
        static::assertEquals(
            ['before' => ['sign', RequestEvents::SIGN_REQUEST]],
            $auth->getEvents()
        );
    }

    public function testKeyAndSecretIsPassedToParams()
    {
        $request = m::mock(RequestInterface::class);
        $event = m::mock(BeforeEvent::class);
        $event->shouldReceive('getRequest')
              ->andReturn($request);

        $config = m::mock(Collection::class);

        $request->shouldReceive('getScheme')
                ->andReturn('https');
        $request->shouldReceive('getConfig')
                ->andReturn($config);

        $config->shouldReceive('get')
               ->with('auth')
               ->andReturn('gigya');
        $config->shouldReceive('get')
               ->with('params')
               ->andReturn([]);

        $config->shouldReceive('set')
               ->with(
                   'params',
                   [
                       'apiKey' => 'key',
                       'secret' => 'secret'
                   ]
               );

        $auth = new GigyaHttpsAuth('key', 'secret');
        $auth->sign($event);
    }

    public function testKeySecretAndUserKeyIsPassedToParams()
    {
        $request = m::mock(RequestInterface::class);
        $event = m::mock(BeforeEvent::class);
        $event->shouldReceive('getRequest')
              ->andReturn($request);

        $config = m::mock(Collection::class);

        $request->shouldReceive('getScheme')
                ->andReturn('https');
        $request->shouldReceive('getConfig')
                ->andReturn($config);

        $config->shouldReceive('get')
               ->with('auth')
               ->andReturn('gigya');
        $config->shouldReceive('get')
               ->with('params')
               ->andReturn([]);

        $config->shouldReceive('set')
               ->with(
                   'params',
                   [
                       'apiKey'  => 'key',
                       'secret'  => 'secret',
                       'userKey' => 'user'
                   ]
               );

        $auth = new GigyaHttpsAuth('key', 'secret', 'user');
        $auth->sign($event);
    }

    public function testKeySecretAndUserKeyIsMergedWithParams()
    {
        $request = m::mock(RequestInterface::class);
        $event = m::mock(BeforeEvent::class);
        $event->shouldReceive('getRequest')
              ->andReturn($request);

        $config = m::mock(Collection::class);

        $request->shouldReceive('getScheme')
                ->andReturn('https');
        $request->shouldReceive('getConfig')
                ->andReturn($config);

        $config->shouldReceive('get')
               ->with('auth')
               ->andReturn('gigya');
        $config->shouldReceive('get')
               ->with('params')
               ->andReturn([
                   'existing' => 'value'
               ]);

        $config->shouldReceive('set')
               ->with(
                   'params',
                   [
                       'existing' => 'value',
                       'apiKey'   => 'key',
                       'secret'   => 'secret',
                       'userKey'  => 'user'
                   ]
               );

        $auth = new GigyaHttpsAuth('key', 'secret', 'user');
        $auth->sign($event);
    }

    public function testAccessors()
    {
        $auth = new GigyaHttpsAuth('key', 'secret', 'user');
        static::assertEquals('key', $auth->getApiKey());
        static::assertEquals('secret', $auth->getSecret());
        static::assertEquals('user', $auth->getUserKey());
    }

    public function testSubscriberDoesNotDoAnythingForNonHttpsRequests()
    {
        $request = m::mock(RequestInterface::class);
        $event = m::mock(BeforeEvent::class);
        $event->shouldReceive('getRequest')
              ->andReturn($request);

        $request->shouldReceive('getScheme')
                ->andReturn('http');

        $auth = new GigyaHttpsAuth('key', 'secret', 'user');
        $auth->sign($event);
    }

    public function testSubscriberDoesNotDoAnythingForNonGigyaAuthRequests()
    {
        $request = m::mock(RequestInterface::class);
        $event = m::mock(BeforeEvent::class);
        $event->shouldReceive('getRequest')
              ->andReturn($request);

        $config = m::mock(Collection::class);
        $request->shouldReceive('getScheme')
                ->andReturn('https');
        $request->shouldReceive('getConfig')
                ->andReturn($config);

        $config->shouldReceive('get')
               ->with('auth')
               ->andReturn('oauth');

        $auth = new GigyaHttpsAuth('key', 'secret', 'user');
        $auth->sign($event);
    }
}
