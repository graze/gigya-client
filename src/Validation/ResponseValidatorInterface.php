<?php

namespace Graze\Gigya\Validation;

use Psr\Http\Message\ResponseInterface;

interface ResponseValidatorInterface
{
    /**
     * Throws exceptions if any errors are found
     *
     * @param ResponseInterface $response
     * @return bool
     */
    public function validate(ResponseInterface $response);

    /**
     * @param ResponseInterface $response
     * @return void
     */
    public function assert(ResponseInterface $response);
}
