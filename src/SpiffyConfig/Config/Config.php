<?php

namespace SpiffyConfig\Config;

use SpiffyConfig\Builder;
use SpiffyConfig\Resolver;

class Config
{
    /**
     * @var array
     */
    protected $builders;

    /**
     * @var Resolver\ResolverInterface
     */
    protected $resolver;

    /**
     * @param Resolver\ResolverInterface $resolver
     */
    public function __construct(Resolver\ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @return \SpiffyConfig\Resolver\ResolverInterface
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param Builder\BuilderInterface $builder
     * @return $this
     */
    public function addBuilder(Builder\BuilderInterface $builder)
    {
        $this->builders[] = $builder;
        return $this;
    }

    /**
     * @return Builder\BuilderInterface[]
     */
    public function getBuilders()
    {
        return $this->builders;
    }
}
