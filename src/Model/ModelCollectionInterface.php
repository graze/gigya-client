<?php

namespace Graze\Gigya\Model;

interface ModelCollectionInterface extends ModelInterface
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
     * The cursor for the next set of results, if this is null there are no more results
     *
     * @return string|null
     */
    public function getNextCursor();
}
