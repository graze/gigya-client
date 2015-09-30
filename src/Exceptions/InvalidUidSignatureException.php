<?php

namespace Graze\Gigya\Exceptions;

use Mockery\Exception;

class InvalidUidSignatureException extends Exception
{
    /**
     * @param string         $uid
     * @param string         $expected
     * @param string         $signature
     * @param Exception|null $e
     */
    public function __construct($uid, $expected, $signature, Exception $e = null)
    {
        $message = sprintf("The supplied signature for uid: %s does not match.\n Expected '%s'\n Supplied '%s'",
            $uid, $expected, $signature
        );

        if ($e) {
            $message .= "\n" . $e->getMessage();
        }

        parent::__construct($message, 0, $e);
    }
}
