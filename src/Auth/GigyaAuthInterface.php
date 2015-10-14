<?php

namespace Graze\Gigya\Auth;

use GuzzleHttp\Event\SubscriberInterface;

interface GigyaAuthInterface extends SubscriberInterface
{
    /**
     * @return string
     */
    public function getApiKey();

    /**
     * @return string
     */
    public function getSecret();

    /**
     * @return string
     */
    public function getUserKey();
}
