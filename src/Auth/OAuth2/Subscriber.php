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

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;

class Subscriber implements SubscriberInterface
{
    const AUTH_NAME = 'gigya-oauth2';

    /** @var GrantInterface */
    private $grant;

    /**
     * @param GrantInterface $grant
     */
    public function __construct(GrantInterface $grant)
    {
        $this->grant = $grant;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public function getEvents()
    {
        return [
            'before' => ['sign', RequestEvents::SIGN_REQUEST],
            'error'  => ['error', RequestEvents::EARLY],
        ];
    }

    /**
     * Add the authentication params to the request.
     *
     * @param BeforeEvent $event
     */
    public function sign(BeforeEvent $event)
    {
        $request = $event->getRequest();
        if ($request->getScheme() == 'https'
            && $request->getConfig()->get('auth') == static::AUTH_NAME
        ) {
            $token = $this->grant->getToken();

            if (!is_null($token)) {
                $request->addHeader('Authorization', sprintf('OAuth %s', $token->getToken()));
            }
        }
    }

    /**
     * @param ErrorEvent $event
     */
    public function error(ErrorEvent $event)
    {
        $response = $event->getResponse();
        if ($response && $response->getStatusCode() == 401) {
            $request = $event->getRequest();
            if ($request->getScheme() == 'https'
                && $request->getConfig()->get('auth') == static::AUTH_NAME
                && !$request->getConfig()->get('retried')
            ) {
                $token = $this->grant->getToken();
                if (!is_null($token)) {
                    $request->getConfig()->set('retried', true);
                    $event->intercept($event->getClient()->send($request));
                }
            }
        }
    }
}
