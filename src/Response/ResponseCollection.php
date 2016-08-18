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
use Illuminate\Support\Collection;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class ResponseCollection extends Response implements ResponseCollectionInterface
{
    /**
     * @var int
     */
    protected $count;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var string
     */
    protected $nextCursor;

    /**
     * @var array
     */
    protected $results;

    /**
     * ResponseCollection constructor.
     *
     * @param GuzzleResponseInterface $response
     */
    public function __construct(GuzzleResponseInterface $response)
    {
        parent::__construct($response);
        $this->count = (int) $this->popField('objectsCount');
        $this->total = (int) $this->popField('totalCount');
        $this->nextCursor = $this->popField('nextCursorId');
        $this->results = (array) $this->popField('results');
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return new Collection($this->results);
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * The cursor for the next set of results, if this is null there are no more results.
     *
     * @return string|null
     */
    public function getNextCursor()
    {
        return $this->nextCursor;
    }
}
