<?php

namespace SpiffyConfig\Builder;

interface BuilderInterface
{
    /**
     * @param array|\Traversable $result
     * @return array
     */
    public function build($result);

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options);
}
