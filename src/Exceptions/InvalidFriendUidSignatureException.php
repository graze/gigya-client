<?php

namespace Graze\Gigya\Exceptions;

use Exception;

class InvalidFriendUidSignatureException extends Exception
{
    /**
     * @param string         $uid
     * @param string         $friendUid
     * @param string         $expected
     * @param string         $signature
     * @param Exception|null $e
     */
    public function __construct($uid, $friendUid, $expected, $signature, Exception $e = null)
    {
        $message = sprintf(
            "The supplied signature for uid: %s and friendUid: %s does not match.\n Expected '%s'\n Supplied '%s'",
            $uid,
            $friendUid,
            $expected,
            $signature
        );

        parent::__construct($message, 0, $e);
    }
}
