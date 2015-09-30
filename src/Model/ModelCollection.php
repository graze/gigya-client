<?php

namespace Graze\Gigya\Model;

use GuzzleHttp\Message\ResponseInterface;
use Illuminate\Support\Collection;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class ModelCollection extends Model implements ModelCollectionInterface
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
     * {@inheritdoc}
     */
    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);
        $this->count = (int)$this->popField('objectsCount');
        $this->total = (int)$this->popField('totalCount');
        $this->nextCursor = $this->popField('nextCursorId');
        $this->results = $this->popField('results');
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
     * The cursor for the next set of results, if this is null there are no more results
     *
     * @return string|null
     */
    public function getNextCursor()
    {
        return $this->nextCursor;
    }
}
