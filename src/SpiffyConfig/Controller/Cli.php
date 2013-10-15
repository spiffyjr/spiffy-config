<?php

namespace SpiffyConfig\Controller;

use Doctrine\Common\Annotations\AnnotationRegistry;
use SpiffyConfig\ConfigManager;
use SpiffyConfig\ModuleOptions;
use Zend\Console\ColorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ArrayUtils;

class Cli extends AbstractActionController
{
    /**
     * @var ConfigManager
     */
    protected $configManager;

    /**
     * @var ModuleOptions
     */
    protected $options;

    public function buildAction()
    {
        AnnotationRegistry::registerAutoloadNamespace('SpiffyConfig\Annotation', array(__DIR__ . '/../../'));
        
        $this->clearCache();

        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');
        $console->writeLine('building cache, please wait...', ColorInterface::YELLOW);

        $options     = $this->getOptions();
        $collections = $options->getCacheCollections();

        if (!is_array($collections)) {
            $collections = array($collections);
        }

        $config = array();
        foreach ($collections as $name) {
            $config = ArrayUtils::merge($config, $this->getConfigManager()->configure($name));
        }

        $cacheFile    = $options->getCacheFile();
        $fileContents = sprintf('<?php%sreturn %s;%s', PHP_EOL, var_export($config, true), PHP_EOL);
        file_put_contents($cacheFile, $fileContents);

        $console->writeLine('success!', ColorInterface::GREEN);
    }

    public function clearAction()
    {
        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');

        $this->clearCache();
        $console->writeLine('success!', ColorInterface::GREEN);
    }

    /**
     * @param ConfigManager $configManager
     * @return $this
     */
    public function setConfigManager(ConfigManager $configManager)
    {
        $this->configManager = $configManager;
        return $this;
    }

    /**
     * @return ConfigManager
     */
    public function getConfigManager()
    {
        if (!$this->configManager instanceof ConfigManager) {
            $this->configManager = ConfigManager::create($this->getOptions()->toArray());
        }
        return $this->configManager;
    }

    /**
     * @param ModuleOptions $options
     * @return $this
     */
    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return ModuleOptions
     */
    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('SpiffyConfig\ModuleOptions'));
        }
        return $this->options;
    }

    /**
     * Clear the cache file.
     */
    protected function clearCache()
    {
        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');
        $console->writeLine('clearing cache, please wait...', ColorInterface::YELLOW);

        $options = $this->getOptions();
        $file    = $options->getCacheFile();

        if (file_exists($file)) {
            unlink($file);
        }
    }
}
