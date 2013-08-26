<?php

namespace SpiffyConfig\Resolver;

use SpiffyConfig\Builder;

interface ResolverInterface
{
    /**
     * @return $this
     */
    public function reset();

    /**
     * @return array|\Traversable
     */
    public function resolve();

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options);

    /**
     * @return array
     */
    public function getOptions();
}
