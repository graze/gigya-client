<?php

namespace Graze\Gigya\Validation;

use Graze\Gigya\Response\ResponseInterface;

interface ResponseValidatorInterface
{
    /**
     * Is this validate applicable to this response.
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public function canValidate(ResponseInterface $response);

    /**
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public function validate(ResponseInterface $response);

    /**
     * Throws exceptions if any errors are found.
     *
     * @param ResponseInterface $response
     *
     * @return void
     */
    public function assert(ResponseInterface $response);
}
