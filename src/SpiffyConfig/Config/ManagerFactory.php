<?php

namespace SpiffyConfig\Config;

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
        /** @var \SpiffyConfig\Builder\Manager $builderManager */
        /** @var \SpiffyConfig\Resolver\Manager $resolverManager */
        $configManager   = new Manager();
        $options         = $serviceLocator->get('SpiffyConfig\ModuleOptions');
        $builderManager  = $serviceLocator->get('SpiffyConfig\Builder\Manager');
        $resolverManager = $serviceLocator->get('SpiffyConfig\Resolver\Manager');

        $configManager->setServiceLocator($serviceLocator);
        $configManager->setBuilderManager($builderManager);
        $configManager->setResolverManager($resolverManager);

        $config = new Config($options->getCollectionManager());
        $config->configureServiceManager($configManager);

        $handlers = $options->getHandlers();
        foreach ($handlers as $handler) {
            if (is_string($handler)) {
                if ($serviceLocator->has($handler)) {
                    $handler = $serviceLocator->get($handler);
                } else {
                    $handler = new $handler();
                }
            }

            $configManager->addHandler($handler);
        }

        return $configManager;
    }
}
