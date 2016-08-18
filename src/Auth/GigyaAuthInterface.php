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
     * @return string|null
     */
    public function getUserKey();
}
