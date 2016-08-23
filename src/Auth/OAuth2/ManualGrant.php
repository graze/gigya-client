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

class ManualGrant implements GrantInterface
{
    /** @var AccessToken|null */
    private $token;

    /**
     * @param AccessToken|null $token
     */
    public function __construct(AccessToken $token = null)
    {
        $this->token = $token;
    }

    /**
     * @return AccessToken|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param AccessToken $token
     *
     * @return $this
     */
    public function setToken(AccessToken $token)
    {
        $this->token = $token;
        return $this;
    }
}
