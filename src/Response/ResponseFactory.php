<?php

namespace Graze\Gigya\Response;

use Graze\Gigya\Validation\GigyaResponseValidatorInterface;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class ResponseFactory
{
    /**
     * @var GigyaResponseValidatorInterface
     */
    private $validator;

    /**
     * @param GigyaResponseValidatorInterface $validator
     */
    public function __construct(GigyaResponseValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Pass in json decoded response here
     *
     * @param GuzzleResponseInterface $response
     * @return ResponseInterface
     */
    public function getResponse(GuzzleResponseInterface $response)
    {
        $this->validator->assert($response);
        $body = json_decode($response->getBody(), true);
        if (array_key_exists('results', $body)) {
            return new ResponseCollection($response);
        } else {
            return new Response($response);
        }
    }
}
