<?php

namespace Graze\Gigya\Exceptions;

use Exception;
use Graze\Gigya\Validation\SignatureValidator;

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
            SignatureValidator::TIMESTAMP_OFFSET,
            time()
        );

        parent::__construct($message, 0, $e);
    }
}
