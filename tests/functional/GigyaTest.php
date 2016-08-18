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

namespace Graze\Gigya\Test\Functional;

use Graze\Gigya\Exception\InvalidTimestampException;
use Graze\Gigya\Exception\InvalidUidSignatureException;
use Graze\Gigya\Gigya;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Graze\Gigya\Validation\Signature;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Subscriber\Mock;
use Mockery as m;

class GigyaTest extends TestCase
{
    /**
     * @param Gigya       $gigya
     * @param string|null $body  Optional body text
     *
     * @return History
     */
    public function setUpGigyaHistory(Gigya $gigya, $body = null)
    {
        $history = new History();
        $mock    = new Mock([
            new Response(
                '200',
                [],
                Stream::factory(
                    $body ?: TestFixtures::getFixture('basic')
                )
            ),
            new Response(
                '200',
                [],
                Stream::factory(
                    $body ?: TestFixtures::getFixture('basic')
                )
            ),
            new Response(
                '200',
                [],
                Stream::factory(
                    $body ?: TestFixtures::getFixture('basic')
                )
            ),
        ]);
        $gigya->addSubscriber($history);
        $gigya->addSubscriber($mock);

        return $history;
    }

    public function testAuthInjectsKeyAndSecretIntoParams()
    {
        $client  = new Gigya('key', 'secret');
        $history = $this->setUpGigyaHistory($client);

        $response = $client->accounts()->getAccountInfo();

        static::assertEquals(0, $response->getErrorCode());
        static::assertEquals(1, $history->count());
        $request = $history->getLastRequest();
        static::assertEquals(
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo?apiKey=key&secret=secret',
            $request->getUrl()
        );
        $query = $request->getQuery();
        static::assertArrayHasKey('apiKey', $query);
        static::assertArrayHasKey('secret', $query);
        static::assertEquals('key', $query['apiKey']);
        static::assertEquals('secret', $query['secret']);
    }

    public function testAuthInjectsKeySecretAndUserKeyIntoParams()
    {
        $client  = new Gigya('key', 'secret', null, 'userKey');
        $history = $this->setUpGigyaHistory($client);

        $response = $client->accounts()->getAccountInfo();

        static::assertEquals(0, $response->getErrorCode());
        static::assertEquals(1, $history->count());
        $request = $history->getLastRequest();
        static::assertEquals(
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo?apiKey=key&secret=secret&userKey=userKey',
            $request->getUrl()
        );
        $query = $request->getQuery();
        static::assertArrayHasKey('apiKey', $query);
        static::assertArrayHasKey('secret', $query);
        static::assertArrayHasKey('userKey', $query);
        static::assertEquals('key', $query['apiKey']);
        static::assertEquals('secret', $query['secret']);
        static::assertEquals('userKey', $query['userKey']);
    }

    public function testUidSignatureWhenValidDoesNotThrowException()
    {
        $uid       = 'diofu90ifgdf';
        $timestamp = time();

        $signatureValidator = new Signature();
        $signature          = $signatureValidator->calculateSignature($timestamp . '_' . $uid, 'secret');

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

        $client  = new Gigya('key', 'secret');
        $history = $this->setUpGigyaHistory($client, $body);

        $response = $client->accounts()->getAccountInfo(['uid' => $uid]);
        static::assertEquals(0, $response->getErrorCode());
        static::assertEquals(1, $history->count());
        $request = $history->getLastRequest();
        static::assertEquals(
            "https://accounts.eu1.gigya.com/accounts.getAccountInfo?uid=$uid&apiKey=key&secret=secret",
            $request->getUrl()
        );
        $query = $request->getQuery();
        static::assertArrayHasKey('apiKey', $query);
        static::assertArrayHasKey('secret', $query);
        static::assertArrayHasKey('uid', $query);
        static::assertEquals('key', $query['apiKey']);
        static::assertEquals('secret', $query['secret']);
        static::assertEquals($uid, $query['uid']);

        $data = $response->getData();
        static::assertEquals($uid, $data->get('UID'));
        static::assertEquals($signature, $data->get('UIDSignature'));
        static::assertEquals($timestamp, $data->get('signatureTimestamp'));
    }

    public function testUidSignatureWhenIncorrectTimestampThrowsAnException()
    {
        $uid       = 'diofu90ifgdf';
        $timestamp = time() - 181;

        $signatureValidator = new Signature();
        $signature          = $signatureValidator->calculateSignature($timestamp . '_' . $uid, 'secret');

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

        $client = new Gigya('key', 'secret');
        $this->setUpGigyaHistory($client, $body);

        static::setExpectedException(
            InvalidTimestampException::class
        );

        $client->accounts()->getAccountInfo(['uid' => $uid]);
    }

    public function testUidSignatureWhenInvalidSignatureThrowsAnException()
    {
        $uid       = 'diofu90ifgdf';
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

        $client = new Gigya('key', 'secret');
        $this->setUpGigyaHistory($client, $body);

        static::setExpectedException(
            InvalidUidSignatureException::class
        );

        $client->accounts()->getAccountInfo(['uid' => $uid]);
    }

    public function testRequestWillThrowTimestampExceptionWhenBothTimestampAndSignatureAreInvalid()
    {
        $uid       = 'diofu90ifgdf';
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

        $client = new Gigya('key', 'secret');
        $this->setUpGigyaHistory($client, $body);

        static::setExpectedException(
            InvalidTimestampException::class
        );

        $client->accounts()->getAccountInfo(['uid' => $uid]);
    }

    public function testGigyaWillTriggerSubscriberOnlyWhenItIsAddedInARequest()
    {
        $client = new Gigya('key', 'secret');
        $this->setUpGigyaHistory($client);

        $client->accounts()->getAccountInfo();

        $subscriber = m::mock(SubscriberInterface::class);
        $subscriber->shouldReceive('getEvents')
                   ->andReturn([
                       'complete' => ['someMethod'],
                   ]);

        $client->addSubscriber($subscriber);

        $subscriber->shouldReceive('someMethod')
                   ->once();

        $client->accounts()->getAccountInfo();

        $client->removeSubscriber($subscriber);

        $client->accounts()->getAccountInfo();
    }
}
