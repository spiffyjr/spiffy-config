<?php

namespace SpiffyConfig\Annotation\Route;

use Doctrine\Common\Annotations\Annotation;

abstract class AbstractRoute extends Annotation
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $controller;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $parent;

    /**
     * @var bool
     */
    public $mayTerminate = true;

    /**
     * @var array
     */
    public $options = array();

    /**
     * @var int
     */
    public $priority;
}
