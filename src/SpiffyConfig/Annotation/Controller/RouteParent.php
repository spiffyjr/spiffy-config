<?php

namespace SpiffyConfig\Annotation\Controller;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
class RouteParent extends Annotation
{
    /**
     * @var string
     */
    public $action;
}