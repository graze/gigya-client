<?php

namespace Graze\Gigya;

use Graze\Gigya\Exceptions\InvalidUidSignatureException;
use Graze\Gigya\Exceptions\InvalidTimestampException;

class SignatureValidation
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
        if (abs(time() - $timestamp) > static::TIMESTAMP_OFFSET) {
            return false;
        }
        $baseString = $timestamp . '_' . $uid;
        $expected = $this->calculateSignature($baseString, $secret);
        return $expected == $signature;
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
        if (abs(time() - $timestamp) > static::TIMESTAMP_OFFSET) {
            throw new InvalidTimestampException($timestamp);
        }
        $baseString = $timestamp . '_' . $uid;
        $expected = $this->calculateSignature($baseString, $secret);
        if ($expected != $signature) {
            throw new InvalidUidSignatureException($uid, $expected, $signature);
        }
        return true;
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
