<?php
/**
 * This file is part of graze/gigya-client
 *
 * Copyright (c) 2016 Nature Delivered Ltd. <https://www.graze.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://github.com/graze/gigya-client/blob/master/LICENSE.md
 * @link    https://github.com/graze/gigya-client
 */

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
