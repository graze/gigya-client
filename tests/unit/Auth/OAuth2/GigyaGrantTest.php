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

    /**
     * @param string      $apiKey
     * @param string      $secret
     * @param string|null $userKey
     *
     * @return GigyaGrant
     */
    public function getGrant($apiKey, $secret, $userKey = null)
    {
        $this->gigya = m::mock(Gigya::class);
        return new GigyaGrant($this->gigya, $apiKey, $secret, $userKey);
    }

    public function testInstanceOf()
    {
        $grant = $this->getGrant('key', 'secret');
        static::assertInstanceOf(GrantInterface::class, $grant);
    }

    public function testGetTokenWhenOneHasNotBeenSet()
    {
        $grant = $this->getGrant('key', 'secret');

        $response = m::mock(ResponseInterface::class);

        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'client_id'     => 'key',
                        'client_secret' => 'secret',
                        'grant_type'    => 'none',
                    ], ['auth' => 'none'])
                    ->andReturn($response);

        $response->shouldReceive('getErrorCode')
                 ->andReturn(ErrorCode::OK);
        $data = m::mock(Collection::class);
        $response->shouldReceive('getData')
            ->andReturn($data);
        $data->shouldReceive('get')
             ->with('access_token')
             ->andReturn('some_token');
        $data->shouldReceive('get')
             ->with('expires_in', null)
             ->andReturn(3600);

        $token = $grant->getToken();

        $expires = (new DateTime())->add(new DateInterval(sprintf('PT%dS', 3600)));

        static::assertEquals('some_token', $token->getToken());
        static::assertLessThanOrEqual($expires, $token->getExpires());
        static::assertFalse($token->isExpired());

        static::assertSame($token, $grant->getToken(), 'Calling getToken again, should return the same token');
    }

    public function testGetTokenWithNoExpiry()
    {
        $grant = $this->getGrant('key', 'secret');

        $response = m::mock(ResponseInterface::class);

        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'client_id'     => 'key',
                        'client_secret' => 'secret',
                        'grant_type'    => 'none',
                    ], ['auth' => 'none'])
                    ->andReturn($response);

        $response->shouldReceive('getErrorCode')
                 ->andReturn(ErrorCode::OK);
        $data = m::mock(Collection::class);
        $response->shouldReceive('getData')
                 ->andReturn($data);
        $data->shouldReceive('get')
             ->with('access_token')
             ->andReturn('some_token');
        $data->shouldReceive('get')
             ->with('expires_in', null)
             ->andReturn(null);

        $token = $grant->getToken();

        static::assertEquals('some_token', $token->getToken());
        static::assertNull($token->getExpires());
        static::assertFalse($token->isExpired());

        static::assertSame($token, $grant->getToken(), 'Calling getToken again, should return the same token');
    }

    public function testGetTokenThatExpiresCallGetTokenAgain()
    {
        $grant = $this->getGrant('key', 'secret');

        $response = m::mock(ResponseInterface::class);

        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'client_id'     => 'key',
                        'client_secret' => 'secret',
                        'grant_type'    => 'none',
                    ], ['auth' => 'none'])
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
        $data->shouldReceive('get')
             ->with('expires_in', null)
             ->twice()
             ->andReturn(0);

        $token = $grant->getToken();

        $expires = new DateTime();

        static::assertEquals('some_token', $token->getToken());
        static::assertLessThanOrEqual($expires, $token->getExpires());
        static::assertTrue($token->isExpired());

        $newToken = $grant->getToken();

        static::assertNotSame($token, $newToken, 'Calling getToken again, should return the same token');

        static::assertEquals('some_token', $token->getToken());
        static::assertLessThanOrEqual($expires, $token->getExpires());
        static::assertTrue($token->isExpired());
    }

    public function testNonOkResponseReturnsANullToken()
    {
        $grant = $this->getGrant('key', 'secret');

        $response = m::mock(ResponseInterface::class);

        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'client_id'     => 'key',
                        'client_secret' => 'secret',
                        'grant_type'    => 'none',
                    ], ['auth' => 'none'])
                    ->andReturn($response);

        $response->shouldReceive('getErrorCode')
                 ->andReturn(ErrorCode::ERROR_GENERAL_SERVER_ERROR);

        $token = $grant->getToken();

        static::assertNull($token);
    }
}
