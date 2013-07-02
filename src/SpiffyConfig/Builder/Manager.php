<?php

namespace SpiffyConfig\Builder;

use Zend\ServiceManager\ServiceManager;

class Manager extends ServiceManager
{
    /**
     * @var array
     */
    protected $invokableClasses = array(
        'controllerloader' => 'SpiffyConfig\Builder\ControllerLoader',
        'router'           => 'SpiffyConfig\Builder\Router',
        'templatemap'      => 'SpiffyConfig\Builder\TemplateMap',
    );
}
