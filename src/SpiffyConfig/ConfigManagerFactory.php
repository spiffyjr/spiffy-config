<?php

namespace SpiffyConfig;

use SpiffyConfig\Resolver\ResolverFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConfigManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ConfigManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyConfig\ModuleOptions $options */
        $options   = $serviceLocator->get('SpiffyConfig\ModuleOptions');
        $manager   = new ConfigManager();

        $resolverFactory = new ResolverFactory();
        foreach ($options->getResolvers() as $spec) {
            $resolverFactory->setSpec($spec);
            $manager->addResolver($resolverFactory->createService($serviceLocator));
        }

        foreach ($options->getConfigListeners() as $listener) {
            if (is_string($listener)) {
                if ($serviceLocator->has($listener)) {
                    $listener = $serviceLocator->get($listener);
                } else {
                    $listener = new $listener;
                }
            }
            $manager->getEventManager()->attach($listener);
        }

        return $manager;
    }
}