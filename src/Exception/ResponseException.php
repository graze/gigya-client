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

namespace Graze\Gigya\Exception;

use Exception;
use Graze\Gigya\Response\ResponseInterface;
use RuntimeException;

/**
 * Class ResponseException.
 *
 * Generic Response Exception
 */
class ResponseException extends RuntimeException
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @param ResponseInterface $response
     * @param string            $message
     * @param Exception|null    $previous
     */
    public function __construct(ResponseInterface $response, $message = '', Exception $previous = null)
    {
        $this->response = $response;

        $message = (($message) ? $message . "\n" : '') .
            $response;

        parent::__construct($message, $response->getErrorCode(), $previous);
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
