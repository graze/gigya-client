<?php

namespace Graze\Gigya\Response;

use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

interface ResponseFactoryInterface
{
    /**
     * Convert a Guzzle response into a Gigya Response
     *
     * @param GuzzleResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function getResponse(GuzzleResponseInterface $response);
}
