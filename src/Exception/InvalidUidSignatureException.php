<?php

namespace Graze\Gigya\Exception;

use Exception;
use Graze\Gigya\Response\ResponseInterface;

class InvalidUidSignatureException extends ResponseException
{
    /**
     * @param string            $uid
     * @param string            $expected
     * @param string            $signature
     * @param ResponseInterface $response
     * @param Exception|null    $e
     */
    public function __construct($uid, $expected, $signature, ResponseInterface $response, Exception $e = null)
    {
        $message = sprintf(
            "The supplied signature for uid: %s does not match.\n Expected '%s'\n Supplied '%s'",
            $uid,
            $expected,
            $signature
        );

        parent::__construct($response, $message, $e);
    }
}
