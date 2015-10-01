<?php

namespace Graze\Gigya\Validation;

use Graze\Gigya\Exceptions\InvalidFriendUidSignatureException;
use Graze\Gigya\Exceptions\InvalidTimestampException;
use Graze\Gigya\Exceptions\InvalidUidSignatureException;

class SignatureValidator
{
    const TIMESTAMP_OFFSET = 180;

    /**
     * Validate the provided Uid signature is valid
     *
     * @param string $uid
     * @param int    $timestamp Unix Timestamp
     * @param string $secret
     * @param string $signature
     * @return bool
     */
    public function validateUid($uid, $timestamp, $secret, $signature)
    {
        return ($this->checkTimestamp($timestamp) &&
            $signature == $this->getUidSignature($uid, $timestamp, $secret));
    }

    /**
     * @param string $uid
     * @param int    $timestamp Unix Timestamp
     * @param string $secret
     * @param string $signature
     * @return bool
     * @throws InvalidTimestampException
     * @throws InvalidUidSignatureException
     */
    public function assertUid($uid, $timestamp, $secret, $signature)
    {
        if (!$this->checkTimestamp($timestamp)) {
            throw new InvalidTimestampException($timestamp);
        }
        $expected = $this->getUidSignature($uid, $timestamp, $secret);
        if ($signature !== $expected) {
            throw new InvalidUidSignatureException($uid, $expected, $signature);
        }
        return true;
    }

    /**
     * @param string $uid
     * @param string $friendUid
     * @param int    $timestamp
     * @param string $secret
     * @param string $signature
     * @return bool
     */
    public function validateFriendUid($uid, $friendUid, $timestamp, $secret, $signature)
    {
        return ($this->checkTimestamp($timestamp) &&
            $signature == $this->getFriendUidSignature($uid, $friendUid, $timestamp, $secret));
    }

    /**
     * @param string $uid
     * @param string $friendUid
     * @param int    $timestamp
     * @param string $secret
     * @param string $signature
     * @return bool
     * @throws InvalidTimestampException
     */
    public function assertFriendUid($uid, $friendUid, $timestamp, $secret, $signature)
    {
        if (!$this->checkTimestamp($timestamp)) {
            throw new InvalidTimestampException($timestamp);
        }
        $expected = $this->getFriendUidSignature($uid, $friendUid, $timestamp, $secret);
        if ($signature !== $expected) {
            throw new InvalidFriendUidSignatureException($uid, $friendUid, $expected, $signature);
        }
        return true;
    }

    /**
     * @param int $timestamp Unix Timestamp
     * @return bool
     */
    private function checkTimestamp($timestamp)
    {
        return (abs(time() - $timestamp) <= static::TIMESTAMP_OFFSET);
    }

    /**
     * @param string $uid
     * @param int    $timestamp
     * @param string $secret
     * @return string
     */
    private function getUidSignature($uid, $timestamp, $secret)
    {
        $baseString = $timestamp . '_' . $uid;
        return $this->calculateSignature($baseString, $secret);
    }

    /**
     * @param string $uid
     * @param string $friendUid
     * @param int    $timestamp
     * @param string $secret
     * @return string
     */
    private function getFriendUidSignature($uid, $friendUid, $timestamp, $secret)
    {
        $baseString = sprintf('%d_%s_%s', $timestamp, $friendUid, $uid);
        return $this->calculateSignature($baseString, $secret);
    }

    /**
     * @param string $baseString
     * @param string $key
     * @return string
     */
    public function calculateSignature($baseString, $key)
    {
        $hmac = hash_hmac("sha1", utf8_encode($baseString), base64_decode($key), true);
        return base64_encode($hmac);
    }
}
