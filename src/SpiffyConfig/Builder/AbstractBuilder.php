<?php

namespace SpiffyConfig\Builder;

abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }
}