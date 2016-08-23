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

use Graze\Gigya\Auth\OAuth2\AccessToken;
use Graze\Gigya\Auth\OAuth2\GrantInterface;
use Graze\Gigya\Auth\OAuth2\ManualGrant;
use Graze\Gigya\Test\TestCase;

class ManualGrantTest extends TestCase
{
    public function testInstanceOf()
    {
        $grant = new ManualGrant();
        static::assertInstanceOf(GrantInterface::class, $grant);
    }

    public function testConstructor()
    {
        $grant = new ManualGrant();
        static::assertNull($grant->getToken());

        $token = new AccessToken('token');
        $grant = new ManualGrant($token);
        static::assertSame($token, $grant->getToken());
    }

    public function testSetter()
    {
        $grant = new ManualGrant();
        static::assertNull($grant->getToken());

        $token = new AccessToken('token');
        static::assertSame($grant, $grant->setToken($token));
        static::assertSame($token, $grant->getToken());
    }
}
