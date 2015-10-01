<?php

namespace Graze\Gigya\Exceptions;

use Exception;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class UnknownResponseException extends Exception
{
    public function __construct(GuzzleResponseInterface $response, $message = '', Exception $e = null)
    {
        $message = "The contents of the response could not be determined. {$message}\n  Body:\n" . $response->getBody();

        parent::__construct($message, 0, $e);
    }
}
