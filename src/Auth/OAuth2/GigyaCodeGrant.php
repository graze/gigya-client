<?php

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
                'authorization_code' => $this->code,
                'redirect_uri'       => $this->redirectUri,
                'grant_type'         => 'code',
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
