<?php

namespace SpiffyConfig\Resolver;

use SpiffyConfig\Builder;

interface ResolverInterface
{
    /**
     * @return ResultInterface
     */
    public function resolve();

    /**
     * @return \Zend\Stdlib\AbstractOptions
     */
    public function getOptions();
}
