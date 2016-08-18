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

use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

interface ResponseFactoryInterface
{
    /**
     * Convert a Guzzle response into a Gigya Response
     *
     * @param GuzzleResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function getResponse(GuzzleResponseInterface $response);
}
