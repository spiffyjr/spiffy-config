<?php

namespace SpiffyConfig\Resolver;

use RuntimeException;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

class Manager extends AbstractPluginManager
{
    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof ResolverInterface) {
            throw new RuntimeException(sprintf(
                'Resolver of type %s is invalid; must implement %s\ResolverInterface',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                __NAMESPACE__
            ));
        }
    }
}
