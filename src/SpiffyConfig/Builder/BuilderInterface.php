<?php

namespace SpiffyConfig\Builder;

use SpiffyConfig\Resolver\ResolverInterface;

interface BuilderInterface
{
    public function setResolver(ResolverInterface $resolver);
    public function build();
}
