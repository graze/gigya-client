<?php

namespace Graze\Gigya\Test\Unit\Validation;

use Graze\Gigya\Exception\InvalidTimestampException;
use Graze\Gigya\Exception\InvalidUidSignatureException;
use Graze\Gigya\Response\ResponseInterface;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Validation\ResponseValidatorInterface;
use Graze\Gigya\Validation\Signature;
use Graze\Gigya\Validation\UidSignatureValidator;
use Illuminate\Support\Collection;
use Mockery as m;
use Mockery\MockInterface;

class UidSignatureValidatorTest extends TestCase
{
    const SECRET = '8j9h0g-opko;dk]=id0f[sjo';

    /**
     * @var MockInterface|Signature
     */
    private $signature;

    /**
     * @var UidSignatureValidator
     */
    private $validator;

    public function setUp()
    {
        $this->signature = m::mock(Signature::class);
        $this->validator = new UidSignatureValidator($this->signature, static::SECRET);
    }

    public function tearDown()
    {
        $this->signature = $this->validator = null;
    }

    public function testInstanceOf()
    {
        static::assertInstanceOf(ResponseValidatorInterface::class, $this->validator);
    }

    public function testUidSignature()
    {
        $response   = m::mock(ResponseInterface::class);
        $collection = m::mock(Collection::class);
        $response->shouldReceive('getData')
                 ->andReturn($collection);
        $collection->shouldReceive('contains')
                   ->with('UID')
                   ->andReturn(true);
        $collection->shouldReceive('contains')
                   ->with('UIDSignature')
                   ->andReturn(true);
        $collection->shouldReceive('contains')
                   ->with('signatureTimestamp')
                   ->andReturn(true);
        $collection->shouldReceive('get')
                   ->with('UID')
                   ->andReturn('some_uid');
        $collection->shouldReceive('get')
                   ->with('UIDSignature')
                   ->andReturn('some_signature');
        $collection->shouldReceive('get')
                   ->with('signatureTimestamp')
                   ->andReturn(12345667);
        static::assertTrue($this->validator->canValidate($response));

        $this->signature->shouldReceive('checkTimestamp')
                        ->with(12345667)
                        ->andReturn(true);
        $this->signature->shouldReceive('getUidSignature')
                        ->with('some_uid', 12345667, static::SECRET)
                        ->andReturn('some_signature');
        static::assertTrue($this->validator->validate($response));
    }

    public function testInvalidUidSignature()
    {
        $response   = m::mock(ResponseInterface::class);
        $collection = m::mock(Collection::class);
        $response->shouldReceive('getData')
                 ->andReturn($collection);
        $collection->shouldReceive('contains')
                   ->with('UID')
                   ->andReturn(true);
        $collection->shouldReceive('contains')
                   ->with('UIDSignature')
                   ->andReturn(true);
        $collection->shouldReceive('contains')
                   ->with('signatureTimestamp')
                   ->andReturn(true);
        $collection->shouldReceive('get')
                   ->with('UID')
                   ->andReturn('some_uid');
        $collection->shouldReceive('get')
                   ->with('UIDSignature')
                   ->andReturn('some_signature');
        $collection->shouldReceive('get')
                   ->with('signatureTimestamp')
                   ->andReturn(12345667);
        static::assertTrue($this->validator->canValidate($response));

        $this->signature->shouldReceive('checkTimestamp')
                        ->with(12345667)
                        ->andReturn(true);
        $this->signature->shouldReceive('getUidSignature')
                        ->with('some_uid', 12345667, static::SECRET)
                        ->andReturn('incorrect_signature');
        static::assertFalse($this->validator->validate($response));

        $response->shouldReceive('getErrorCode')
            ->andReturn(0);

        static::setExpectedException(InvalidUidSignatureException::class);
        $this->validator->assert($response);
    }

    public function testInvalidTimestamp()
    {
        $response   = m::mock(ResponseInterface::class);
        $collection = m::mock(Collection::class);
        $response->shouldReceive('getData')
                 ->andReturn($collection);
        $collection->shouldReceive('contains')
                   ->with('UID')
                   ->andReturn(true);
        $collection->shouldReceive('contains')
                   ->with('UIDSignature')
                   ->andReturn(true);
        $collection->shouldReceive('contains')
                   ->with('signatureTimestamp')
                   ->andReturn(true);
        $collection->shouldReceive('get')
                   ->with('UID')
                   ->andReturn('some_uid');
        $collection->shouldReceive('get')
                   ->with('UIDSignature')
                   ->andReturn('some_signature');
        $collection->shouldReceive('get')
                   ->with('signatureTimestamp')
                   ->andReturn(12345667);
        static::assertTrue($this->validator->canValidate($response));

        $this->signature->shouldReceive('checkTimestamp')
                        ->with(12345667)
                        ->andReturn(false);
        static::assertFalse($this->validator->validate($response));

        $response->shouldReceive('getErrorCode')
                 ->andReturn(0);

        static::setExpectedException(InvalidTimestampException::class);
        $this->validator->assert($response);
    }
}
