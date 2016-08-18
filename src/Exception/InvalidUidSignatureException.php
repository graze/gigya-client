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

class InvalidUidSignatureException extends ResponseException
{
    /**
     * @param string            $uid
     * @param string            $expected
     * @param string            $signature
     * @param ResponseInterface $response
     * @param Exception|null    $previous
     */
    public function __construct($uid, $expected, $signature, ResponseInterface $response, Exception $previous = null)
    {
        $message = sprintf(
            "The supplied signature for uid: %s does not match.\n Expected '%s'\n Supplied '%s'",
            $uid,
            $expected,
            $signature
        );

        parent::__construct($response, $message, $previous);
    }
}
