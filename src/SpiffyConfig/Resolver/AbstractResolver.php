<?php

namespace SpiffyConfig\Resolver;

use SpiffyConfig\Builder\BuilderInterface;

abstract class AbstractResolver implements ResolverInterface
{
    /**
     * @var array
     */
    protected $builders = array();

    /**
     * @return array
     */
    public function getBuilders()
    {
        return $this->builders;
    }

    /**
     * @param BuilderInterface $builder
     * @return $this
     */
    public function addBuilder(BuilderInterface $builder)
    {
        $builder->setResolver($this);
        $this->builders[] = $builder;
        return $this;
    }
}