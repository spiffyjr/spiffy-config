<?php

namespace SpiffyConfig;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\ColorInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface,
    ConsoleUsageProviderInterface
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

        if (!$options->getEnabled()) {
            return;
        }

        AnnotationRegistry::registerAutoloadNamespace('SpiffyConfig\Annotation', array(__DIR__ . '/..'));

        $configManager = $sm->get('SpiffyConfig\ConfigManager');
        $configManager->configure($options->getRuntimeCollection());
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return array(
            $console->colorize('Usage:', ColorInterface::YELLOW),
            '  [options] command [arguments]',
            '',
            $console->colorize('Available Commands:', ColorInterface::YELLOW),
            array(
                $console->colorize('  spiffyconfig build', ColorInterface::GREEN),
                'build, or rebuild if present, the cache'
            ),
            array(
                $console->colorize('  spiffyconfig clear', ColorInterface::GREEN),
                'clear the cache'
            ),
        );
    }
}
