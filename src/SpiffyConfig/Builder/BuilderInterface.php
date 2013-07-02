<?php

namespace SpiffyConfig\Builder;

use SpiffyConfig\Resolver;

interface BuilderInterface
{
    /**
     * @param Resolver\ResultInterface $result
     * @return array
     */
    public function build(Resolver\ResultInterface $result);
}
