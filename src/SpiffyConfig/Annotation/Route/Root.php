<?php

namespace SpiffyConfig\Annotation\Route;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 *
 * Used to define a root route that all children will be prefixed
 * with.
 */
class Root extends Annotation
{
    /**
     * @var string
     */
    public $name;
}
