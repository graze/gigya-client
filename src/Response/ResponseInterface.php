<?php

namespace Graze\Gigya\Response;

use DateTimeInterface;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;
use Illuminate\Support\Collection;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

interface ResponseInterface
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
     * @return GuzzleResponseInterface
     */
    public function getOriginalResponse();

    /**
     * @return string
     */
    public function __toString();
}
