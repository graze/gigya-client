<?php

namespace Graze\Gigya\Exceptions;

use Exception;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class UnknownResponseException extends Exception
{
    public function __construct(GuzzleResponseInterface $response, Exception $e = null)
    {
        $message = "The contents of the response could not be determined.\n  Body:\n" . $response->getBody();

        if ($e) {
            $message .= "\n" . $e->getMessage();
        }

        parent::__construct($message, 0, $e);
    }
}
