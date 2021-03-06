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

namespace Graze\Gigya\Validation;

class Signature
{
    const TIMESTAMP_OFFSET = 180;

    /**
     * @param int $timestamp Unix Timestamp
     *
     * @return bool
     */
    public function checkTimestamp($timestamp)
    {
        return (abs(time() - $timestamp) <= static::TIMESTAMP_OFFSET);
    }

    /**
     * @param string $uid
     * @param int    $timestamp
     * @param string $secret
     *
     * @return string
     */
    public function getUidSignature($uid, $timestamp, $secret)
    {
        $baseString = $timestamp . '_' . $uid;

        return $this->calculateSignature($baseString, $secret);
    }

    /**
     * @param string $uid
     * @param string $friendUid
     * @param int    $timestamp
     * @param string $secret
     *
     * @return string
     */
    public function getFriendUidSignature($uid, $friendUid, $timestamp, $secret)
    {
        $baseString = sprintf('%d_%s_%s', $timestamp, $friendUid, $uid);

        return $this->calculateSignature($baseString, $secret);
    }

    /**
     * @param string $baseString
     * @param string $key
     *
     * @return string
     */
    public function calculateSignature($baseString, $key)
    {
        $hmac = hash_hmac('sha1', utf8_encode($baseString), base64_decode($key), true);

        return base64_encode($hmac);
    }
}
