<?php

namespace Graze\Gigya\Response;

use Graze\Gigya\Validation\ResponseValidatorInterface;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class ResponseFactory
{
    /**
     * @var ResponseValidatorInterface[]
     */
    private $validators = [];

    /**
     * @param ResponseValidatorInterface[] $validators
     */
    public function __construct($validators = [])
    {
        foreach ($validators as $validator) {
            $this->addValidator($validator);
        }
    }

    /**
     * @param ResponseValidatorInterface $validator
     * @return $this
     */
    public function addValidator(ResponseValidatorInterface $validator)
    {
        $this->validators[] = $validator;
        return $this;
    }

    /**
     * Pass in json decoded response here
     *
     * @param GuzzleResponseInterface $response
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
        $this->assert($result);

        return $result;
    }

    /**
     * @param ResponseInterface $response
     */
    private function assert(ResponseInterface $response)
    {
        foreach ($this->validators as $validator) {
            if ($validator->canValidate($response)) {
                $validator->assert($response);
            }
        }
    }
}
