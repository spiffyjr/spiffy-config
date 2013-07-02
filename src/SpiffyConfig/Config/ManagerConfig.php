<?php

namespace SpiffyConfig\Config;

use SpiffyConfig\Config;
use Zend\ServiceManager;

class ManagerConfig extends ServiceManager\Config
{
    /**
     * {@inheritDoc}
     */
    public function configureServiceManager(ServiceManager\ServiceManager $serviceManager)
    {
        if (!$serviceManager instanceof Config\Manager) {
            // todo: throw exception
            echo 'invalid service manager';
            exit;
        }

        $builderManager  = $serviceManager->getBuilderManager();
        $resolverManager = $serviceManager->getResolverManager();
        $factoryConfig   = isset($this->config['factory_config']) ? $this->config['factory_config'] : null;

        if (is_array($factoryConfig)) {
            foreach ($factoryConfig as $name => $configs) {
                $collection = new Collection();

                foreach ($configs as $spec) {
                    $resolver = isset($spec['resolver']) ? $spec['resolver'] : null;
                    if (!$resolver) {
                        // todo: add exception
                        echo 'every collection must have a resolver';
                        exit;
                    }

                    $builders = isset($spec['builders']) ? $spec['builders'] : null;
                    if (!$builders || !is_array($builders) || empty($builders)) {
                        // todo: add exception
                        echo 'no builders were present';
                        exit;
                    }

                    $config = new Config\Config($resolverManager->get($resolver));

                    foreach ($builders as $builder) {
                        $config->addBuilder($builderManager->get($builder));
                    }

                    $collection->addConfig($config);
                }

                $serviceManager->setService($name, $collection);
            }
        }

        parent::configureServiceManager($serviceManager);
    }
}