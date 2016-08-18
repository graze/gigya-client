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
