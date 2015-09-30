<?php

namespace Graze\Gigya\Model;

use Graze\Gigya\Validation\ResponseValidatorInterface;
use Psr\Http\Message\ResponseInterface;

class ModelFactory
{
    /**
     * @var ResponseValidatorInterface
     */
    private $validator;

    /**
     * @param ResponseValidatorInterface $validator
     */
    public function __construct(ResponseValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Pass in json decoded response here
     *
     * @param ResponseInterface $response
     * @return ModelInterface
     */
    public function getModel(ResponseInterface $response)
    {
        $this->validator->assert($response);
        $body = json_decode($response->getBody(), true);
        if (array_key_exists('results', $body)) {
            return new ModelCollection($response);
        } else {
            return new Model($response);
        }
    }
}
