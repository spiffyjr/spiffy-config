<?php

namespace SpiffyConfig\Resolver;

use SpiffyConfig\Builder;

interface ResolverInterface
{
    /**
     * @return ResultInterface
     */
    public function resolve();
}
