<?php

namespace SpiffyConfig\Builder;

use Zend\ServiceManager;

class ManagerFactory implements ServiceManager\FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return Manager
     */
    public function createService(ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyConfig\ModuleOptions $options */
        $options = $serviceLocator->get('SpiffyConfig\ModuleOptions');
        $manager = new Manager();
        $config  = new ServiceManager\Config($options->getBuilderManager());

        $manager->addPeeringServiceManager($serviceLocator);
        $config->configureServiceManager($manager);

        return $manager;
    }
}
