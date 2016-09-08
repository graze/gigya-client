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
    /** @var AccessToken|null */
    private $token;
    /** @var Gigya */
    private $gigya;

    /**
     * GigyaGrant constructor.
     *
     * @param Gigya $gigya
     */
    public function __construct(Gigya $gigya)
    {
        $this->gigya = $gigya;
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
                'grant_type' => 'none',
            ], ['auth' => 'credentials']);
            if ($response->getErrorCode() == ErrorCode::OK) {
                $data = $response->getData();
                $token = $data->get('access_token');
                $expires = null;
                if ($data->has('expires_in')) {
                    $expires = (new DateTime())->add(new DateInterval(sprintf('PT%dS', $data->get('expires_in', 0))));
                }
                $this->token = new AccessToken($token, $expires);
            }
        }

        return $this->token;
    }
}
