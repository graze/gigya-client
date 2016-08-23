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

namespace Graze\Gigya\Auth\OAuth2;

use DateTime;
use DateTimeInterface;

class AccessToken
{
    /** @var string */
    private $token;
    /** @var DateTimeInterface|null */
    private $expires;

    /**
     * AccessToken constructor.
     *
     * @param string                 $token
     * @param DateTimeInterface|null $expires
     */
    public function __construct($token, DateTimeInterface $expires = null)
    {
        $this->token = $token;
        $this->expires = $expires;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return AccessToken
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Check if a token is expired
     *
     * @return bool
     */
    public function isExpired()
    {
        if (!is_null($this->expires)) {
            $diff = $this->expires->format('U') - (new DateTime())->format('U');
            return $diff <= 0;
        }
        return false;
    }

    /**
     * @param DateTimeInterface|null $expires
     *
     * @return AccessToken
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }
}
