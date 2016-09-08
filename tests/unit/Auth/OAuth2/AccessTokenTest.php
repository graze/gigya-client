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
use Graze\Gigya\Auth\OAuth2\AccessToken;
use Graze\Gigya\Test\TestCase;

class AccessTokenTest extends TestCase
{
    public function testTokenWithNoExpiryIsNeverExpired()
    {
        $token = new AccessToken('token');
        static::assertEquals('token', $token->getToken());
        static::assertNull($token->getExpires());
        static::assertFalse($token->isExpired());
    }

    public function testTokenWithExpiryIsShown()
    {
        $expires = (new DateTime())->add(new DateInterval('PT60S'));
        $token = new AccessToken('token', $expires);
        static::assertEquals('token', $token->getToken());
        static::assertLessThanOrEqual($expires, $token->getExpires());
        static::assertFalse($token->isExpired());
    }

    public function testTokenWithExpiryAsNowIsExpired()
    {
        $expires = new DateTime();
        $token = new AccessToken('token', $expires);
        static::assertEquals('token', $token->getToken());
        static::assertLessThanOrEqual($expires, $token->getExpires());
        static::assertTrue($token->isExpired());
    }

    public function testProperties()
    {
        $token = new AccessToken('token');
        static::assertEquals('token', $token->getToken());
        static::assertNull($token->getExpires());
        static::assertFalse($token->isExpired());

        $token->setToken('new token');
        static::assertEquals('new token', $token->getToken());

        $expires = (new DateTime())->add(new DateInterval('PT60S'));

        $token->setExpires($expires);
        static::assertEquals($expires, $token->getExpires());
        static::assertFalse($token->isExpired());

        $expires = new DateTime();
        $token->setExpires($expires);
        static::assertTrue($token->isExpired());
    }
}
