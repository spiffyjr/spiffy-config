<?php

namespace SpiffyConfig\Builder;

use SpiffyConfig\Resolver\ResolverInterface;

abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * @param ResolverInterface $resolver
     * @return $this
     */
    public function setResolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
        return $this;
    }
}
