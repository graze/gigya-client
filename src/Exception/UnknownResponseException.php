<?php

namespace Graze\Gigya\Exception;

use Exception;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class UnknownResponseException extends Exception
{
    /**
     * @var GuzzleResponseInterface
     */
    private $response;

    /**
     * @param GuzzleResponseInterface|null $response
     * @param string                       $message
     * @param Exception|null               $e
     */
    public function __construct(GuzzleResponseInterface $response = null, $message = '', Exception $e = null)
    {
        $message = "The contents of the response could not be determined. {$message}" .
            ($response ? "\n Body:\n" . $response->getBody() : '');

        $this->response = $response;

        parent::__construct($message, 0, $e);
    }

    /**
     * @return GuzzleResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
