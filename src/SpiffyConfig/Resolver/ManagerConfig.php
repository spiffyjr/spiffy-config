<?php

namespace SpiffyConfig\Resolver;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

class ManagerConfig extends Config
{
    /**
     * {@inheritDoc}
     */
    public function configureServiceManager(ServiceManager $serviceManager)
    {
        $factoryConfig = isset($this->config['factory_config']) ? $this->config['factory_config'] : null;
        if (is_array($factoryConfig)) {
            foreach ($factoryConfig as $resolverName => $spec) {
                $serviceManager->setFactory($resolverName, new ResolverFactory($spec));
            }
        }

        parent::configureServiceManager($serviceManager);
    }
}
