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

interface ResponseCollectionInterface extends ResponseInterface
{
    /**
     * @return int
     */
    public function getCount();

    /**
     * @return int
     */
    public function getTotal();

    /**
     * The cursor for the next set of results, if this is null there are no more results.
     *
     * @return string|null
     */
    public function getNextCursor();
}
