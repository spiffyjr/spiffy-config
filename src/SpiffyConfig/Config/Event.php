<?php

namespace SpiffyConfig\Config;

use SpiffyConfig\Builder;
use SpiffyConfig\Resolver;
use Zend\EventManager;

class Event extends EventManager\Event
{
    /**
     * @var Builder\BuilderInterface
     */
    protected $builder;

    /**
     * @var Resolver\ResolverInterface
     */
    protected $resolver;

    /**
     * @param \SpiffyConfig\Builder\BuilderInterface $builder
     * @return $this
     */
    public function setBuilder($builder)
    {
        $this->builder = $builder;
        return $this;
    }

    /**
     * @return \SpiffyConfig\Builder\BuilderInterface
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @param \SpiffyConfig\Resolver\ResolverInterface $resolver
     * @return $this
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;
        return $this;
    }

    /**
     * @return \SpiffyConfig\Resolver\ResolverInterface
     */
    public function getResolver()
    {
        return $this->resolver;
    }
}
