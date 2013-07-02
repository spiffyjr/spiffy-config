<?php

namespace SpiffyConfig\Resolver;

abstract class AbstractResolver implements ResolverInterface
{
    /**
     * @var \Zend\Stdlib\AbstractOptions
     */
    protected $options;

    /**
     * @return \Zend\Stdlib\AbstractOptions
     */
    public function getOptions()
    {
        return $this->options;
    }
}
