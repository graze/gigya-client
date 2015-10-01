<?php

namespace Graze\Gigya\Test\Unit\Validation;

use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Graze\Gigya\Validation\GuzzleResponseValidator;
use Graze\Gigya\Validation\SignatureValidator;
use Mockery as m;

class GuzzleResponseValidatorTest extends TestCase
{
    /**
     * @var GuzzleResponseValidator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new GuzzleResponseValidator('secret');
    }

    public function testInstanceOf()
    {
        static::assertInstanceOf('Graze\Gigya\Validation\GuzzleResponseValidatorInterface', $this->validator);
    }

    public function testValidResponse()
    {
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.search_simple'));

        static::assertTrue($this->validator->validate($response));
        $this->validator->assert($response);
    }

    public function testUidSignatureWhenValidDoesNotThrowException()
    {
        $uid = 'diofu90ifgdf';
        $timestamp = time();

        $signatureValidator = new SignatureValidator();
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

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn($body);

        static::assertTrue($this->validator->validate($response));
        $this->validator->assert($response);
    }

    public function testUidSignatureWithInvalidTimestampWillThrowException()
    {
        $uid = 'diofu90ifgdf';
        $timestamp = time() - 181;

        $signatureValidator = new SignatureValidator();
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

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn($body);

        static::assertFalse($this->validator->validate($response));

        static::setExpectedException(
            'Graze\Gigya\Exceptions\InvalidTimestampException',
            sprintf(
                "The supplied timestamp: %d is more than 180 seconds different to now: %d",
                $timestamp,
                time()
            )
        );

        $this->validator->assert($response);
    }

    public function testUidSignatureWithInvalidSignatureWillThrowException()
    {
        $uid = 'diofu90ifgdf';
        $timestamp = time();

        $signatureValidator = new SignatureValidator();
        $signature = $signatureValidator->calculateSignature($timestamp . '_' . $uid, 'secret');

        $body = sprintf(
            '{
            "UID": "%s",
            "UIDSignature": "invalidSignature",
            "signatureTimestamp": "%d",
            "statusCode": 200,
            "errorCode": 0,
            "statusReason": "OK",
            "callId": "123456",
            "time": "2015-03-22T11:42:25.943Z"
        }',
            $uid,
            $timestamp
        );

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn($body);

        static::assertFalse($this->validator->validate($response));

        static::setExpectedException(
            'Graze\Gigya\Exceptions\InvalidUidSignatureException',
            sprintf(
                "The supplied signature for uid: diofu90ifgdf does not match.\n Expected '%s'\n Supplied 'invalidSignature'",
                $signature
            )
        );

        $this->validator->assert($response);
    }

    public function testNoBodyWillFail()
    {
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn('');

        static::assertFalse($this->validator->validate($response));

        static::setExpectedException(
            'Graze\Gigya\Exceptions\UnknownResponseException',
            'The contents of the response could not be determined'
        );

        $this->validator->assert($response);
    }

    public function testInvalidBody()
    {
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('invalid_json'));

        static::assertFalse($this->validator->validate($response));

        static::setExpectedException(
            'Graze\Gigya\Exceptions\UnknownResponseException',
            'The contents of the response could not be determined'
        );

        $this->validator->assert($response);
    }
}
