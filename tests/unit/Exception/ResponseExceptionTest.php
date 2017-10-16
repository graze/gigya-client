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

namespace Graze\Gigya\Test\Unit\Exception;

use Graze\Gigya\Exception\ResponseException;
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

    /**
     * @expectedException \Graze\Gigya\Exception\ResponseException
     * @expectedExceptionCode    100001
     * @expectedExceptionMessage some description from the response
     */
    public function testExceptionIncludeResponseStringAndCode()
    {
        $response = m::mock(ResponseInterface::class);
        $response->shouldReceive('getErrorCode')
                 ->andReturn(100001);
        $response->shouldReceive('__toString')
                 ->andReturn('some description from the response');
        $exception = new ResponseException($response);

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
