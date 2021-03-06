<?php

namespace SpiffyConfig\Resolver;

abstract class AbstractResolver implements ResolverInterface
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

    /**
     * @return \Zend\Stdlib\AbstractOptions
     */
    public function getOptions()
    {
        return $this->options;
    }
}
