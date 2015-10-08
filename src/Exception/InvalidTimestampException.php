<?php

namespace Graze\Gigya\Exception;

use Exception;
use Graze\Gigya\Response\ResponseInterface;
use Graze\Gigya\Validation\Signature;

class InvalidTimestampException extends ResponseException
{
    /**
     * @param int               $timestamp
     * @param ResponseInterface $response
     * @param Exception|null    $e
     */
    public function __construct($timestamp, ResponseInterface $response, Exception $e = null)
    {
        $message = sprintf(
            "The supplied timestamp: %d is more than %d seconds different to now: %d",
            $timestamp,
            Signature::TIMESTAMP_OFFSET,
            time()
        );

        parent::__construct($response, $message, $e);
    }
}
