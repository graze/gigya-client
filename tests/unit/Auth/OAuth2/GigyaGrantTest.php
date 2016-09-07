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

use DateInterval;
use DateTime;
use Graze\Gigya\Auth\OAuth2\GigyaGrant;
use Graze\Gigya\Auth\OAuth2\GrantInterface;
use Graze\Gigya\Gigya;
use Graze\Gigya\Response\ErrorCode;
use Graze\Gigya\Response\ResponseInterface;
use Graze\Gigya\Test\TestCase;
use Illuminate\Support\Collection;
use Mockery as m;

class GigyaGrantTest extends TestCase
{
    /** @var mixed */
    private $gigya;
    /** @var GigyaGrant */
    private $grant;

    public function setUp()
    {
        $this->gigya = m::mock(Gigya::class);
        $this->grant = new GigyaGrant($this->gigya);
    }

    public function testInstanceOf()
    {
        static::assertInstanceOf(GrantInterface::class, $this->grant);
    }

    public function testGetTokenWhenOneHasNotBeenSet()
    {
        $response = m::mock(ResponseInterface::class);

        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'grant_type' => 'none',
                    ], ['auth' => 'credentials'])
                    ->andReturn($response);

        $response->shouldReceive('getErrorCode')
                 ->andReturn(ErrorCode::OK);
        $data = m::mock(Collection::class);
        $response->shouldReceive('getData')
                 ->andReturn($data);
        $data->shouldReceive('get')
             ->with('access_token')
             ->andReturn('some_token');
        $data->shouldReceive('has')
             ->with('expires_in')
             ->andReturn(true);
        $data->shouldReceive('get')
             ->with('expires_in', 0)
             ->andReturn(3600);

        $token = $this->grant->getToken();

        $expires = (new DateTime())->add(new DateInterval(sprintf('PT%dS', 3600)));

        static::assertEquals('some_token', $token->getToken());
        static::assertLessThanOrEqual($expires, $token->getExpires());
        static::assertFalse($token->isExpired());

        static::assertSame($token, $this->grant->getToken(), 'Calling getToken again, should return the same token');
    }

    public function testGetTokenWithNoExpiry()
    {
        $response = m::mock(ResponseInterface::class);

        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'grant_type' => 'none',
                    ], ['auth' => 'credentials'])
                    ->andReturn($response);

        $response->shouldReceive('getErrorCode')
                 ->andReturn(ErrorCode::OK);
        $data = m::mock(Collection::class);
        $response->shouldReceive('getData')
                 ->andReturn($data);
        $data->shouldReceive('get')
             ->with('access_token')
             ->andReturn('some_token');
        $data->shouldReceive('has')
             ->with('expires_in')
             ->andReturn(false);

        $token = $this->grant->getToken();

        static::assertEquals('some_token', $token->getToken());
        static::assertNull($token->getExpires());
        static::assertFalse($token->isExpired());

        static::assertSame($token, $this->grant->getToken(), 'Calling getToken again, should return the same token');
    }

    public function testGetTokenThatExpiresCallGetTokenAgain()
    {
        $response = m::mock(ResponseInterface::class);

        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'grant_type' => 'none',
                    ], ['auth' => 'credentials'])
                    ->twice()
                    ->andReturn($response);

        $response->shouldReceive('getErrorCode')
                 ->twice()
                 ->andReturn(ErrorCode::OK);
        $data = m::mock(Collection::class);
        $response->shouldReceive('getData')
                 ->andReturn($data);
        $data->shouldReceive('get')
             ->with('access_token')
             ->twice()
             ->andReturn('some_token');
        $data->shouldReceive('has')
             ->with('expires_in')
             ->twice()
             ->andReturn(true);
        $data->shouldReceive('get')
             ->with('expires_in', 0)
             ->twice()
             ->andReturn(0);

        $token = $this->grant->getToken();

        $expires = new DateTime();

        static::assertEquals('some_token', $token->getToken());
        static::assertLessThanOrEqual($expires, $token->getExpires());
        static::assertTrue($token->isExpired());

        $newToken = $this->grant->getToken();

        static::assertNotSame($token, $newToken, 'Calling getToken again, should return the same token');

        static::assertEquals('some_token', $token->getToken());
        static::assertLessThanOrEqual($expires, $token->getExpires());
        static::assertTrue($token->isExpired());
    }

    public function testNonOkResponseReturnsANullToken()
    {
        $response = m::mock(ResponseInterface::class);

        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'grant_type' => 'none',
                    ], ['auth' => 'credentials'])
                    ->andReturn($response);

        $response->shouldReceive('getErrorCode')
                 ->andReturn(ErrorCode::ERROR_GENERAL_SERVER_ERROR);

        $token = $this->grant->getToken();

        static::assertNull($token);
    }
}
