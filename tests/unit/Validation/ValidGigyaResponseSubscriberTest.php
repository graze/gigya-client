<?php

namespace Graze\Gigya\Test\Unit\Validation;

use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Graze\Gigya\Validation\Signature;
use Graze\Gigya\Validation\ValidGigyaResponseSubscriber;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use Mockery as m;

class ValidGigyaResponseSubscriberTest extends TestCase
{
    /**
     * @var ValidGigyaResponseSubscriber
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new ValidGigyaResponseSubscriber();
    }

    public function tearDown()
    {
        $this->validator = null;
    }

    public function testInstanceOf()
    {
        static::assertInstanceOf(SubscriberInterface::class, $this->validator);
    }

    public function testGetEvents()
    {
        static::assertEquals(
            ['complete' => ['onComplete', RequestEvents::VERIFY_RESPONSE]],
            $this->validator->getEvents()
        );
    }

    /**
     * @throws \Graze\Gigya\Exception\UnknownResponseException
     */
    public function testValidResponse()
    {
        $completeEvent = m::mock(CompleteEvent::class);
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $completeEvent->shouldReceive('getResponse')
                      ->andReturn($response);
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.search_simple'));

        $this->validator->onComplete($completeEvent, 'name');
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

        $completeEvent = m::mock(CompleteEvent::class);
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $completeEvent->shouldReceive('getResponse')
                      ->andReturn($response);
        $response->shouldReceive('getBody')->andReturn($body);

        $this->validator->onComplete($completeEvent, 'name');
    }

    public function testNullResponseWillThrowAnException()
    {
        $completeEvent = m::mock(CompleteEvent::class);
        $completeEvent->shouldReceive('getResponse')
                      ->andReturn(null);

        static::setExpectedException(
            'Graze\Gigya\Exception\UnknownResponseException',
            "The contents of the response could not be determined. No response provided"
        );

        $this->validator->onComplete($completeEvent, 'name');
    }

    public function testMissingFieldWillThrowAnException()
    {
        $completeEvent = m::mock(CompleteEvent::class);
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $completeEvent->shouldReceive('getResponse')
                      ->andReturn($response);
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('missing_field'));

        static::setExpectedException(
            'Graze\Gigya\Exception\UnknownResponseException',
            "The contents of the response could not be determined. Missing required field: 'statusReason'"
        );

        $this->validator->onComplete($completeEvent, 'name');
    }

    public function testNoBodyWillFail()
    {
        $completeEvent = m::mock(CompleteEvent::class);
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $completeEvent->shouldReceive('getResponse')
                      ->andReturn($response);
        $response->shouldReceive('getBody')->andReturn('');

        static::setExpectedException(
            'Graze\Gigya\Exception\UnknownResponseException',
            'The contents of the response could not be determined'
        );

        $this->validator->onComplete($completeEvent, 'name');
    }

    public function testInvalidBody()
    {
        $completeEvent = m::mock(CompleteEvent::class);
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $completeEvent->shouldReceive('getResponse')
                      ->andReturn($response);
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('invalid_json'));

        static::setExpectedException(
            'Graze\Gigya\Exception\UnknownResponseException',
            "The contents of the response could not be determined. Could not decode the body"
        );

        $this->validator->onComplete($completeEvent, 'name');
    }
}
