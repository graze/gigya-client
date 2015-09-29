<?php

namespace Graze\Gigya\Model;

use DateTimeInterface;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

interface ModelInterface
{
    /**
     * @return int
     */
    public function getErrorCode();

    /**
     * @return string|null
     */
    public function getErrorMessage();

    /**
     * @return string|null
     */
    public function getErrorDetails();

    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @return string
     */
    public function getStatusReason();

    /**
     * @return string
     */
    public function getCallId();

    /**
     * @return DateTimeInterface
     */
    public function getTime();

    /**
     * @return Collection
     */
    public function getData();

    /**
     * @return ResponseInterface
     */
    public function getOriginalResponse();
}
