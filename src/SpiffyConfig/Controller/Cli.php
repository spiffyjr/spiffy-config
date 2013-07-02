<?php

namespace SpiffyConfig\Controller;

use SpiffyConfig\Config;
use SpiffyConfig\Handler;
use SpiffyConfig\ModuleOptions;
use Zend\Console\ColorInterface;
use Zend\Mvc\Controller\AbstractActionController;

class Cli extends AbstractActionController
{
    /**
     * @var Config\Manager
     */
    protected $configManager;

    /**
     * @var Handler\Autoload
     */
    protected $autoloadHandler;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @throws \SpiffyConfig\Exception\RuntimeException
     */
    public function buildAction()
    {
        $this->clearCache();

        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');
        $console->writeLine('building cache, please wait...', ColorInterface::YELLOW);

        $manager = $this->getConfigManager();
        $manager->configure($this->getModuleOptions()->getCliCollection());

        $console->writeLine('success!', ColorInterface::GREEN);
    }

    /**
     * @throws \SpiffyConfig\Exception\RuntimeException
     */
    public function clearAction()
    {
        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');

        $this->clearCache();

        $console->writeLine('success!', ColorInterface::GREEN);
    }

    /**
     * @param Config\Manager $configManager
     * @return $this
     */
    public function setConfigManager(Config\Manager $configManager)
    {
        $configManager->addHandler($this->getAutoloadHandler());
        $this->configManager = $configManager;
        return $this;
    }

    /**
     * @return Config\Manager
     */
    public function getConfigManager()
    {
        if (!$this->configManager instanceof Config\Manager) {
            $this->setConfigManager($this->getServiceLocator()->get('SpiffyConfig\Config\Manager'));
        }
        return $this->configManager;
    }

    /**
     * @param Handler\Autoload $autoloadHandler
     * @return $this
     */
    public function setAutoloadHandler(Handler\Autoload $autoloadHandler)
    {
        $this->autoloadHandler = $autoloadHandler;
        return $this;
    }

    /**
     * @return Handler\Autoload
     */
    public function getAutoloadHandler()
    {
        if (!$this->autoloadHandler instanceof Handler\Autoload) {
            $this->setAutoloadHandler($this->getServiceLocator()->get('SpiffyConfig\Handler\Autoload'));
        }
        return $this->autoloadHandler;
    }

    /**
     * @param \SpiffyConfig\ModuleOptions $moduleOptions
     * @return $this
     */
    public function setModuleOptions($moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
        return $this;
    }

    /**
     * @return \SpiffyConfig\ModuleOptions
     */
    public function getModuleOptions()
    {
        if (!$this->moduleOptions instanceof ModuleOptions) {
            $this->setModuleOptions($this->getServiceLocator()->get('SpiffyConfig\ModuleOptions'));
        }
        return $this->moduleOptions;
    }

    /**
     * Clear the cache file.
     */
    protected function clearCache()
    {
        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');
        $console->writeLine('clearing cache, please wait...', ColorInterface::YELLOW);

        $options = $this->getModuleOptions();
        $file    = $options->getAutoloadFile();

        if (file_exists($file)) {
            unlink($file);
        }
    }
}
