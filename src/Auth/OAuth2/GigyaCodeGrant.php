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

class GigyaCodeGrant implements GrantInterface
{
    /** @var Gigya */
    private $gigya;
    /** @var string */
    private $code;
    /** @var string */
    private $redirectUri;
    /** @var AccessToken|null */
    private $token;

    /**
     * @param Gigya  $gigya
     * @param string $code
     * @param string $redirectUri
     */
    public function __construct(Gigya $gigya, $code, $redirectUri)
    {
        $this->gigya = $gigya;
        $this->code = $code;
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return AccessToken|null
     */
    public function getToken()
    {
        if (is_null($this->token)) {
            $response = $this->gigya->socialize()->getToken([
                'code'         => $this->code,
                'redirect_uri' => $this->redirectUri,
                'grant_type'   => 'authorization_code',
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
