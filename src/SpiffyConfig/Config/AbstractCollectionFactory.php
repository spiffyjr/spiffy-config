<?php

namespace SpiffyConfig\Config;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractCollectionFactory implements AbstractFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return null !== $this->getConfig($serviceLocator, $name);
    }

    /**
     * {@inheritDoc}
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        /** @var \SpiffyConfig\Config\Manager $serviceLocator */
        $sl = $serviceLocator->getServiceLocator();

        $configSpec      = $this->getConfig($serviceLocator, $name);
        $collection      = new Collection();
        $builderManager  = $sl->get('SpiffyConfig\Builder\Manager');
        $resolverManager = $sl->get('SpiffyConfig\Resolver\Manager');

        foreach ($configSpec as $name => $spec) {
            if (!isset($spec['resolver'])) {
                throw new \RuntimeException(sprintf('Missing resolver for collection "%s"'), $name);
            }
            $config = new Config($resolverManager->get($spec['resolver']));

            if (isset($spec['builders'])) {
                foreach ($spec['builders'] as $builder) {
                    $config->addBuilder($builderManager->get($builder));
                }
            }

            $collection->addConfig($config);
        }

        return $collection;
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
        $config = $config['spiffy_config']['collections'];

        return isset($config[$name]) ? $config[$name] : null;
    }
}