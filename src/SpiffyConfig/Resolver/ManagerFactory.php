<?php

namespace SpiffyConfig\Resolver;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Manager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyConfig\ModuleOptions $options */
        $options = $serviceLocator->get('SpiffyConfig\ModuleOptions');
        $manager = new Manager();
        $manager->setServiceLocator($serviceLocator);

        $config = new Config($options->getResolverManager());
        $config->configureServiceManager($manager);

        return $manager;
    }
}
