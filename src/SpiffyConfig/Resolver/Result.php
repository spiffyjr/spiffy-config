<?php

namespace SpiffyConfig\Resolver;

use Iterator;

class Result implements ResultInterface
{
    /**
     * @var Iterator
     */
    protected $iterator;

    /**
     * @param Iterator $iterator
     */
    public function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return $this->iterator;
    }
}
