<?php

namespace Graze\Gigya\Test\Unit\Exception;

use Graze\Gigya\Exceptions\ResponseException;
use Graze\Gigya\Response\ResponseInterface;
use Graze\Gigya\Test\TestCase;
use Mockery as m;

class ResponseExceptionTest extends TestCase
{
    public function testInstanceOfRuntimeException()
    {
        $response = m::mock(ResponseInterface::class);
        $response->shouldReceive('getErrorCode')
                 ->andReturn(0);
        $exception = new ResponseException($response);

        static::assertInstanceOf('RuntimeException', $exception);
    }

    public function testExceptionIncludeResponseStringAndCode()
    {
        $response = m::mock(ResponseInterface::class);
        $response->shouldReceive('getErrorCode')
                 ->andReturn(100001);
        $response->shouldReceive('__toString')
                 ->andReturn('some description from the response');
        $exception = new ResponseException($response);

        static::setExpectedException(
            ResponseException::class,
            'some description from the response',
            100001
        );

        throw $exception;
    }

    public function testGetResponse()
    {
        $response = m::mock(ResponseInterface::class);
        $response->shouldReceive('getErrorCode')
                 ->andReturn(100001);
        $exception = new ResponseException($response);

        static::assertSame($response, $exception->getResponse());
    }
}
