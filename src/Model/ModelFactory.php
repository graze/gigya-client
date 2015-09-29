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
     */
    public function getModel(ResponseInterface $response)
    {
        $text = $response->getBody();
        if (!$this->validBody($text)) {
            throw new UnknownResponseException($response);
        }
        $body = json_decode($response->getBody(), true);
        if (is_array($body) && array_key_exists('results', $body)) {
            return new ModelCollection($response);
        } else {
            return new Model($response);
        }
    }

    /**
     * @param string $body
     * @return bool
     */
    private function validBody($body)
    {
        $object = json_decode($body);
        return (is_object($object) && property_exists($object, 'statusCode'));
    }
}
