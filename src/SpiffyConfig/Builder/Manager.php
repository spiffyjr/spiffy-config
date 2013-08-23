<?php

namespace SpiffyConfig\Builder;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\ServiceManager;

class Manager extends AbstractPluginManager
{
    protected $aliases = array(
        'controllermanager' => 'servicemanager'
    );

    /**
     * @var array
     */
    protected $invokableClasses = array(
        'servicemanager' => 'SpiffyConfig\Builder\ServiceBuilder',
        'routes'         => 'SpiffyConfig\Builder\RouteBuilder',
        'templatemap'    => 'SpiffyConfig\Builder\TemplateMapBuilder',
    );

    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof BuilderInterface) {
            throw new \RuntimeException(sprintf(
                'Builder of type %s is invalid; must implement %s\BuilderInterface',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                __NAMESPACE__
            ));
        }
    }
}
