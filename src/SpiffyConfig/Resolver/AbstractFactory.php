<?php

namespace SpiffyConfig\Resolver;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractFactory implements AbstractFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return null !== $this->getConfig($serviceLocator, $requestedName);
    }

    /**
     * {@inheritDoc}
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $configSpec = $this->getConfig($serviceLocator, $requestedName);
        $factory    = new ResolverFactory($configSpec);

        return $factory->createService($serviceLocator);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @return array|null
     */
    protected function getConfig(ServiceLocatorInterface $serviceLocator, $name)
    {
        /** @var \SpiffyConfig\Resolver\Manager $serviceLocator */
        $sl     = $serviceLocator->getServiceLocator();
        $config = $sl->get('Configuration');
        $config = $config['spiffy_config']['resolvers'];

        return isset($config[$name]) ? $config[$name] : null;
    }
}