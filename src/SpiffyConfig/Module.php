<?php

namespace SpiffyConfig;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var \Zend\Mvc\MvcEvent $e */
        $app = $e->getApplication();
        $sm  = $app->getServiceManager();

        /** @var \SpiffyConfig\ModuleOptions $options */
        $options = $sm->get('SpiffyConfig\ModuleOptions');

        if ($options->getEnableProduction()) {
            return;
        }

        $configManager = $sm->get('SpiffyConfig\ConfigManager');
        $configManager->configure();
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
}