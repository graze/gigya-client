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

namespace Graze\Gigya\Test\Integration;

use Graze\Gigya\Exception\InvalidTimestampException;
use Graze\Gigya\Exception\InvalidUidSignatureException;
use Graze\Gigya\Gigya;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Graze\Gigya\Validation\Signature;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Mockery as m;
use Psr\Http\Message\RequestInterface;

class GigyaTest extends TestCase
{
    /**
     * @param string|null $body Optional body text
     *
     * @return HandlerStack
     */
    public function setupHandler($body = null)
    {
        $mockHandler = new MockHandler(array_pad(
            [],
            3,
            new Response(
                '200',
                [],
                $body ?: TestFixtures::getFixture('basic')
            )
        ));

        return new HandlerStack($mockHandler);
    }

    public function testAuthInjectsKeyAndSecretIntoParams()
    {
        $handler = $this->setupHandler();
        $client = new Gigya('key', 'secret', null, null, ['guzzle' => ['handler' => $handler]]);
        $store = [];
        $handler->push(Middleware::history($store));

        $response = $client->accounts()->getAccountInfo();

        static::assertEquals(0, $response->getErrorCode());
        static::assertCount(1, $store);
        $log = array_pop($store);

        /** @var RequestInterface $request */
        $request = $log['request'];
        parse_str($request->getBody()->__toString(), $body);
        static::assertEquals('key', $body['apiKey']);
        static::assertEquals('secret', $body['secret']);

        static::assertEquals(
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            $request->getUri()->__toString()
        );
    }

    public function testAuthInjectsKeySecretAndUserKeyIntoParams()
    {
        $handler = $this->setupHandler();
        $client = new Gigya('key', 'secret', null, 'userKey', ['guzzle' => ['handler' => $handler]]);
        $store = [];
        $handler->push(Middleware::history($store));

        $response = $client->accounts()->getAccountInfo();

        static::assertEquals(0, $response->getErrorCode());
        static::assertCount(1, $store);
        $log = array_pop($store);
        static::assertArrayHasKey('request', $log);
        /** @var RequestInterface $request */
        $request = $log['request'];
        static::assertInstanceOf(RequestInterface::class, $request);
        parse_str($request->getBody()->__toString(), $body);
        static::assertEquals('key', $body['apiKey']);
        static::assertEquals('secret', $body['secret']);
        static::assertEquals(
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            $request->getUri()->__toString()
        );
    }

    public function testUidSignatureWhenValidDoesNotThrowException()
    {
        $uid = 'diofu90ifgdf';
        $timestamp = time();

        $signatureValidator = new Signature();
        $signature = $signatureValidator->calculateSignature($timestamp . '_' . $uid, 'secret');

        $body = sprintf(
            '{
            "UID": "%s",
            "UIDSignature": "%s",
            "signatureTimestamp": "%d",
            "statusCode": 200,
            "errorCode": 0,
            "statusReason": "OK",
            "callId": "123456",
            "time": "2015-03-22T11:42:25.943Z"
        }',
            $uid,
            $signature,
            $timestamp
        );

        $handler = $this->setupHandler($body);
        $client = new Gigya('key', 'secret', null, null, ['guzzle' => ['handler' => $handler]]);
        $store = [];
        $handler->push(Middleware::history($store));

        $response = $client->accounts()->getAccountInfo(['uid' => $uid]);
        static::assertEquals(0, $response->getErrorCode());
        static::assertCount(1, $store);
        $log = array_pop($store);

        /** @var RequestInterface $request */
        $request = $log['request'];
        static::assertInstanceOf(RequestInterface::class, $request);
        parse_str($request->getBody()->__toString(), $body);
        static::assertEquals('key', $body['apiKey']);
        static::assertEquals('secret', $body['secret']);
        static::assertEquals(
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            $request->getUri()->__toString()
        );

        $data = $response->getData();
        static::assertEquals($uid, $data->get('UID'));
        static::assertEquals($signature, $data->get('UIDSignature'));
        static::assertEquals($timestamp, $data->get('signatureTimestamp'));
    }

    public function testUidSignatureWhenIncorrectTimestampThrowsAnException()
    {
        $uid = 'diofu90ifgdf';
        $timestamp = time() - 181;

        $signatureValidator = new Signature();
        $signature = $signatureValidator->calculateSignature($timestamp . '_' . $uid, 'secret');

        $body = sprintf(
            '{
            "UID": "%s",
            "UIDSignature": "%s",
            "signatureTimestamp": "%d",
            "statusCode": 200,
            "errorCode": 0,
            "statusReason": "OK",
            "callId": "123456",
            "time": "2015-03-22T11:42:25.943Z"
        }',
            $uid,
            $signature,
            $timestamp
        );

        $handler = $this->setupHandler($body);
        $client = new Gigya('key', 'secret', null, null, ['guzzle' => ['handler' => $handler]]);

        static::expectException(InvalidTimestampException::class);

        $client->accounts()->getAccountInfo(['uid' => $uid]);
    }

    public function testUidSignatureWhenInvalidSignatureThrowsAnException()
    {
        $uid = 'diofu90ifgdf';
        $timestamp = time();

        $body = sprintf(
            '{
            "UID": "%s",
            "UIDSignature": "%s",
            "signatureTimestamp": "%d",
            "statusCode": 200,
            "errorCode": 0,
            "statusReason": "OK",
            "callId": "123456",
            "time": "2015-03-22T11:42:25.943Z"
        }',
            $uid,
            'invalid',
            $timestamp
        );

        $handler = $this->setupHandler($body);
        $client = new Gigya('key', 'secret', null, null, ['guzzle' => ['handler' => $handler]]);

        static::expectException(InvalidUidSignatureException::class);

        $client->accounts()->getAccountInfo(['uid' => $uid]);
    }

    public function testRequestWillThrowTimestampExceptionWhenBothTimestampAndSignatureAreInvalid()
    {
        $uid = 'diofu90ifgdf';
        $timestamp = time() - 181;

        $body = sprintf(
            '{
            "UID": "%s",
            "UIDSignature": "%s",
            "signatureTimestamp": "%d",
            "statusCode": 200,
            "errorCode": 0,
            "statusReason": "OK",
            "callId": "123456",
            "time": "2015-03-22T11:42:25.943Z"
        }',
            $uid,
            'invalid',
            $timestamp
        );

        $handler = $this->setupHandler($body);
        $client = new Gigya('key', 'secret', null, null, ['guzzle' => ['handler' => $handler]]);

        static::expectException(InvalidTimestampException::class);

        $client->accounts()->getAccountInfo(['uid' => $uid]);
    }

    public function testGigyaWillTriggerSubscriberOnlyWhenItIsAddedInARequest()
    {
        $handler = $this->setupHandler();
        $client = new Gigya('key', 'secret', null, null, ['guzzle' => ['handler' => $handler]]);

        $client->accounts()->getAccountInfo();

        $called = 0;

        $fn = function (callable $handler) use (&$called) {
            return function (RequestInterface $request, $options) use ($handler, &$called) {
                $called++;
                return $handler($request, $options);
            };
        };

        $client->addHandler($fn);

        $client->accounts()->getAccountInfo();

        $this->assertEquals(1, $called);

        $client->removeHandler($fn);

        $client->accounts()->getAccountInfo();

        $this->assertEquals(1, $called);
    }
}
