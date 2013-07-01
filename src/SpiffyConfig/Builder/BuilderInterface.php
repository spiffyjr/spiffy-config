<?php

namespace SpiffyConfig\Builder;

use SpiffyConfig\Resolver\ResolverInterface;

interface BuilderInterface
{
    /**
     * @param ResolverInterface $resolver
     * @return BuilderInterface
     */
    public function setResolver(ResolverInterface $resolver);

    /**
     * @return mixed
     */
    public function build();
}
