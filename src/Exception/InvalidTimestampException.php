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
use Graze\Gigya\Validation\Signature;

class InvalidTimestampException extends ResponseException
{
    /**
     * @param int               $timestamp
     * @param ResponseInterface $response
     * @param Exception|null    $e
     */
    public function __construct($timestamp, ResponseInterface $response, Exception $e = null)
    {
        $message = sprintf(
            'The supplied timestamp: %d is more than %d seconds different to now: %d',
            $timestamp,
            Signature::TIMESTAMP_OFFSET,
            time()
        );

        parent::__construct($response, $message, $e);
    }
}
