<?php

namespace Graze\Gigya\Exceptions;

use Exception;
use Graze\Gigya\SignatureValidation;

class InvalidTimestampException extends Exception
{
    /**
     * @param int            $timestamp
     * @param Exception|null $e
     */
    public function __construct($timestamp, Exception $e = null)
    {
        $message = sprintf(
            "The supplied timestamp: %d is more than %d seconds different to now: %d",
            $timestamp,
            SignatureValidation::TIMESTAMP_OFFSET,
            time()
        );

        if ($e) {
            $message .= "\n " . $e->getMessage();
        }

        parent::__construct($message, 0, $e);
    }
}
