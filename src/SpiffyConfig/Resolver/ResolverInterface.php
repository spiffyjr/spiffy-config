<?php

namespace SpiffyConfig\Resolver;

use SpiffyConfig\Builder\BuilderInterface;

interface ResolverInterface
{
    public function addBuilder(BuilderInterface $builder);
    public function getBuilders();
    public function resolve();
}
