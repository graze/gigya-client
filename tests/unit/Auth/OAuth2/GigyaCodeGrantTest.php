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
use Graze\Gigya\Auth\OAuth2\GigyaCodeGrant;
use Graze\Gigya\Auth\OAuth2\GrantInterface;
use Graze\Gigya\Gigya;
use Graze\Gigya\Response\ErrorCode;
use Graze\Gigya\Response\ResponseInterface;
use Graze\Gigya\Test\TestCase;
use Illuminate\Support\Collection;
use Mockery as m;

class GigyaCodeGrantTest extends TestCase
{
    /** @var mixed */
    private $gigya;

    /**
     * @param string $code
     * @param string $uri
     *
     * @return GigyaCodeGrant
     */
    private function getGrant($code, $uri)
    {
        $this->gigya = m::mock(Gigya::class);
        return new GigyaCodeGrant($this->gigya, $code, $uri);
    }

    public function testInstanceOf()
    {
        $grant = $this->getGrant('code', 'uri');
        static::assertInstanceOf(GrantInterface::class, $grant);
    }

    public function testGetToken()
    {
        $grant = $this->getGrant('code', 'uri');

        $response = m::mock(ResponseInterface::class);
        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'code'         => 'code',
                        'redirect_uri' => 'uri',
                        'grant_type'   => 'authorization_code',
                    ], ['auth' => 'credentials'])
                    ->andReturn($response);

        $response->shouldReceive('getErrorCode')
                 ->andReturn(ErrorCode::OK);
        $data = m::mock(Collection::class);
        $response->shouldReceive('getData')
                 ->andReturn($data);

        $data->shouldReceive('get')
             ->with('access_token')
             ->andReturn('some_access_token');
        $data->shouldReceive('has')
             ->with('expires_in')
             ->andReturn(true);
        $data->shouldReceive('get')
             ->with('expires_in', 0)
             ->andReturn(3600);

        $token = $grant->getToken();

        $expires = (new DateTime())->add(new DateInterval(sprintf('PT%dS', 3600)));

        static::assertEquals('some_access_token', $token->getToken());
        static::assertLessThanOrEqual($expires, $token->getExpires());
        static::assertFalse($token->isExpired());
    }

    public function testGetTokenWithNoExpiresReturnsAnUnExpiredToken()
    {
        $grant = $this->getGrant('code', 'uri');

        $response = m::mock(ResponseInterface::class);
        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'code'         => 'code',
                        'redirect_uri' => 'uri',
                        'grant_type'   => 'authorization_code',
                    ], ['auth' => 'credentials'])
                    ->andReturn($response);

        $response->shouldReceive('getErrorCode')
                 ->andReturn(ErrorCode::OK);
        $data = m::mock(Collection::class);
        $response->shouldReceive('getData')
                 ->andReturn($data);

        $data->shouldReceive('get')
             ->with('access_token')
             ->andReturn('some_access_token');
        $data->shouldReceive('has')
             ->with('expires_in')
             ->andReturn(false);

        $token = $grant->getToken();

        static::assertEquals('some_access_token', $token->getToken());
        static::assertNull($token->getExpires());
        static::assertFalse($token->isExpired());
    }

    public function testGetTokenCalledTwiceWillAlwaysReturnTheOriginalToken()
    {
        $grant = $this->getGrant('code', 'uri');

        $response = m::mock(ResponseInterface::class);
        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'code'         => 'code',
                        'redirect_uri' => 'uri',
                        'grant_type'   => 'authorization_code',
                    ], ['auth' => 'credentials'])
                    ->andReturn($response);

        $response->shouldReceive('getErrorCode')
                 ->andReturn(ErrorCode::OK);
        $data = m::mock(Collection::class);
        $response->shouldReceive('getData')
                 ->andReturn($data);

        $data->shouldReceive('get')
             ->with('access_token')
             ->andReturn('some_access_token');
        $data->shouldReceive('has')
             ->with('expires_in')
             ->andReturn(true);
        $data->shouldReceive('get')
             ->with('expires_in', 0)
             ->andReturn(0);

        $token = $grant->getToken();

        $expires = new DateTime();

        static::assertEquals('some_access_token', $token->getToken());
        static::assertLessThanOrEqual($expires, $token->getExpires());
        static::assertTrue($token->isExpired());

        static::assertSame($token, $grant->getToken());
    }

    public function testGetTokenWithUserKey()
    {
        $grant = $this->getGrant('code', 'uri', 'siteKey', 'siteSecret', 'userKey');

        $response = m::mock(ResponseInterface::class);
        $this->gigya->shouldReceive('socialize->getToken')
                    ->with([
                        'code'         => 'code',
                        'redirect_uri' => 'uri',
                        'grant_type'   => 'authorization_code',
                    ], ['auth' => 'credentials'])
                    ->andReturn($response);

        $response->shouldReceive('getErrorCode')
                 ->andReturn(ErrorCode::OK);
        $data = m::mock(Collection::class);
        $response->shouldReceive('getData')
                 ->andReturn($data);

        $data->shouldReceive('get')
             ->with('access_token')
             ->andReturn('some_access_token');
        $data->shouldReceive('has')
             ->with('expires_in')
             ->andReturn(true);
        $data->shouldReceive('get')
             ->with('expires_in', null)
             ->andReturn(3600);

        $token = $grant->getToken();

        $expires = (new DateTime())->add(new DateInterval(sprintf('PT%dS', 3600)));

        static::assertEquals('some_access_token', $token->getToken());
        static::assertLessThanOrEqual($expires, $token->getExpires());
        static::assertFalse($token->isExpired());
    }
}
