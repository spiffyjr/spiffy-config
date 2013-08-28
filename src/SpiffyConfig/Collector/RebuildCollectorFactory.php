<?php

namespace SpiffyConfig\Collector;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RebuildCollectorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return RebuildCollector
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RebuildCollector($serviceLocator->get('SpiffyConfig\ModuleOptions'));
    }
}
