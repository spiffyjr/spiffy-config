<?php

namespace SpiffyConfig\Controller;

use SpiffyConfig\ConfigManager;
use Zend\Console\ColorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ArrayUtils;

class Cli extends AbstractActionController
{
    /**
     * @var ConfigManager
     */
    protected $configManager;

    public function buildAction()
    {
        $this->clearCache();

        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');
        $console->writeLine('building cache, please wait...', ColorInterface::YELLOW);

        $options     = $this->getOptions();
        $collections = $options['cache_collections'];

        if (!is_array($collections)) {
            $collections = array($collections);
        }

        $config = array();
        foreach ($collections as $name) {
            $config = ArrayUtils::merge($config, $this->getConfigManager()->configure($name));
        }

        $cacheFile    = $options['cache_file'];
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
        $configManager->addHandler($this->getConfigWriter());
        $this->configManager = $configManager;
        return $this;
    }

    /**
     * @return ConfigManager
     */
    public function getConfigManager()
    {
        if (!$this->configManager instanceof ConfigManager) {
            $this->configManager = ConfigManager::create($this->getOptions());
        }
        return $this->configManager;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $config = $this->getServiceLocator()->get('Configuration');
        return $config['spiffy_config'];
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
        $file    = $options['cache_file'];

        if (file_exists($file)) {
            unlink($file);
        }
    }
}
