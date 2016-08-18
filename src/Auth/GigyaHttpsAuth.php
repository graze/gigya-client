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

namespace Graze\Gigya\Auth;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\RequestEvents;

class GigyaHttpsAuth implements GigyaAuthInterface
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var null|string
     */
    private $userKey;

    /**
     * @param string      $apiKey
     * @param string      $secret
     * @param string|null $userKey
     */
    public function __construct($apiKey, $secret, $userKey = null)
    {
        $this->apiKey = $apiKey;
        $this->secret = $secret;
        $this->userKey = $userKey;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public function getEvents()
    {
        return ['before' => ['sign', RequestEvents::SIGN_REQUEST]];
    }

    /**
     * Add the authentication params to the request.
     *
     * @param BeforeEvent $event
     */
    public function sign(BeforeEvent $event)
    {
        $request = $event->getRequest();
        if ($request->getScheme() == 'https' && $request->getConfig()->get('auth') == 'gigya') {
            $query = $request->getQuery();
            $query['apiKey'] = $this->apiKey;
            $query['secret'] = $this->secret;
            if ((bool) $this->userKey) {
                $query['userKey'] = $this->userKey;
            }
        }
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    public function getUserKey()
    {
        return $this->userKey;
    }
}
