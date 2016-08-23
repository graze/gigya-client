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

use DateInterval;
use DateTime;
use Graze\Gigya\Gigya;
use Graze\Gigya\Response\ErrorCode;

class GigyaGrant implements GrantInterface
{
    /** @var string */
    private $apiKey;
    /** @var string */
    private $secret;
    /** @var null|string */
    private $userKey;
    /** @var AccessToken|null */
    private $token;
    /**
     * @var Gigya
     */
    private $gigya;

    /**
     * GigyaGrant constructor.
     *
     * @param Gigya       $gigya
     * @param string      $apiKey
     * @param string      $secret
     * @param string|null $userKey
     */
    public function __construct(Gigya $gigya, $apiKey, $secret, $userKey = null)
    {
        $this->gigya = $gigya;
        $this->apiKey = $apiKey;
        $this->secret = $secret;
        $this->userKey = $userKey;
        $this->token = null;
    }

    /**
     * @return AccessToken|null
     */
    public function getToken()
    {
        if (!is_null($this->token) && $this->token->isExpired()) {
            $this->token = null;
        }

        if (is_null($this->token)) {
            $response = $this->gigya->socialize()->getToken([
                'client_id'     => $this->userKey ?: $this->apiKey,
                'client_secret' => $this->secret,
                'grant_type'    => 'none',
            ], ['auth' => 'none']);
            if ($response->getErrorCode() == ErrorCode::OK) {
                $data = $response->getData();
                $token = $data->get('access_token');
                $expiresIn = $data->get('expires_in', null);
                $expires = null;
                if (!is_null($expiresIn)) {
                    $expires = (new DateTime())->add(new DateInterval(sprintf('PT%dS', $expiresIn)));
                }
                $this->token = new AccessToken($token, $expires);
            }
        }

        return $this->token;
    }
}
