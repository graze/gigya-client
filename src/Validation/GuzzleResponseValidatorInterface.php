<?php

namespace Graze\Gigya\Validation;

// use Psr\Http\Message\ResponseInterface; Guzzle v6
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

interface GuzzleResponseValidatorInterface
{
    /**
     * Throws exceptions if any errors are found
     *
     * @param GuzzleResponseInterface $response
     * @return bool
     */
    public function validate(GuzzleResponseInterface $response);

    /**
     * @param GuzzleResponseInterface $response
     * @return void
     */
    public function assert(GuzzleResponseInterface $response);
}
