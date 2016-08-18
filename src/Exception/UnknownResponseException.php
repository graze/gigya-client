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
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class UnknownResponseException extends Exception
{
    /**
     * @var GuzzleResponseInterface
     */
    private $response;

    /**
     * @param GuzzleResponseInterface|null $response
     * @param string                       $message
     * @param Exception|null               $previous
     */
    public function __construct(GuzzleResponseInterface $response = null, $message = '', Exception $previous = null)
    {
        $message = "The contents of the response could not be determined. {$message}" .
            ($response ? "\n Body:\n" . $response->getBody() : '');

        $this->response = $response;

        parent::__construct($message, 0, $previous);
    }

    /**
     * @return GuzzleResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
