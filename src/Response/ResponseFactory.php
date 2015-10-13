<?php

namespace Graze\Gigya\Response;

use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * Pass in json decoded response here.
     *
     * @param GuzzleResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function getResponse(GuzzleResponseInterface $response)
    {
        $body = json_decode($response->getBody(), true);
        if (array_key_exists('results', $body)) {
            $result = new ResponseCollection($response);
        } else {
            $result = new Response($response);
        }
        return $result;
    }
}
