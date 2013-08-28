<?php

namespace SpiffyConfig;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\ColorInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ServiceManager;
use Zend\Stdlib\ArrayUtils;

class Module implements
    ConfigProviderInterface,
    ConsoleUsageProviderInterface,
    InitProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function init(ModuleManagerInterface $manager)
    {
        $eventManager = $manager->getEventManager();
        $eventManager->attach(
            ModuleEvent::EVENT_LOAD_MODULES_POST,
            array($this, 'onLoadModules'),
            10000
        );
    }

    /**
     * loadModules callback. This sets up the config manager, builds the configuration, and sets the config
     * listenere's merged config
     *
     * @param  $event
     */
    public function onLoadModules(ModuleEvent $event)
    {
        $configListener = $event->getConfigListener();
        $config         = $configListener->getMergedConfig(false);
        $options        = new ModuleOptions(isset($config['spiffy_config']) ? $config['spiffy_config'] : array());

        if ($options->getEnabled() !== true || ($options->getRequireKey() && !isset($_GET[$options->getKey()]))) {
            return;
        }

        AnnotationRegistry::registerAutoloadNamespace('SpiffyConfig\Annotation', array(__DIR__ . '/..'));

        $manager     = ConfigManager::create($options->toArray());
        $collections = $options->getRuntimeCollections();
        $collections = is_array($collections) ? $collections : array($collections);

        $spiffyConfig = array();
        foreach ($collections as $name) {
            $spiffyConfig = ArrayUtils::merge($spiffyConfig, $manager->configure($name));
        }
        $configListener->setMergedConfig(ArrayUtils::merge($config, $spiffyConfig));

        if ($options->getRequireKey()) {
            $fileContents = sprintf('<?php%sreturn %s;%s', PHP_EOL, var_export($spiffyConfig, true), PHP_EOL);
            file_put_contents($options->getCacheFile(), $fileContents);
        }
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
