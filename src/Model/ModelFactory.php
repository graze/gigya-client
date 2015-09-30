<?php

namespace Graze\Gigya\Model;

use Graze\Gigya\Exceptions\UnknownResponseException;
use Psr\Http\Message\ResponseInterface;

class ModelFactory
{
    /**
     * Pass in json decoded response here
     *
     * @param ResponseInterface $response
     * @return ModelInterface
     * @throws UnknownResponseException
     */
    public function getModel(ResponseInterface $response)
    {
        $body = json_decode($response->getBody(), true);
        if (!$this->validBody($body)) {
            throw new UnknownResponseException($response);
        }
        if (array_key_exists('results', $body)) {
            return new ModelCollection($response);
        } else {
            return new Model($response);
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    private function validBody($data)
    {
        return (is_array($data) && array_key_exists('statusCode', $data));
    }
}
