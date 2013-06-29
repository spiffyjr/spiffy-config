<?php

namespace SpiffyConfig\Annotation\Route;

use Doctrine\Common\Annotations\Annotation;

abstract class AbstractType extends Annotation
{
    /**
     * Key used to denote what the 'route' key is.
     *
     * @var string
     */
    public $routeKey = 'route';

    /**
     * @var string
     */
    public $name = null;

    /**
     * @var string
     */
    public $type;
}
