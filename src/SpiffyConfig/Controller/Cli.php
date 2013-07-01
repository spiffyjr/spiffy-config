<?php

namespace SpiffyConfig\Controller;

use SpiffyConfig\CacheListener;
use SpiffyConfig\ConfigManager;
use Zend\Console\ColorInterface;
use Zend\Mvc\Controller\AbstractActionController;

class Cli extends AbstractActionController
{
    /**
     * @var ConfigManager
     */
    protected $configManager;

    /**
     * @var CacheListener
     */
    protected $cacheListener;

    /**
     * @throws \SpiffyConfig\Exception\RuntimeException
     */
    public function buildAction()
    {
        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');
        $console->writeLine('building cache, please wait...', ColorInterface::YELLOW);

        $manager = $this->getconfigManager();
        $manager->getEventManager()->attach($this->getCacheListener());
        $manager->configure();

        $console->writeLine('success!', ColorInterface::GREEN);
    }

    /**
     * @throws \SpiffyConfig\Exception\RuntimeException
     */
    public function clearAction()
    {
        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');
        $console->writeLine('clearing cache, please wait...', ColorInterface::YELLOW);

        $builder = $this->getconfigManager();
        $builder->getCacheAdapter()->removeItem(configManager::CACHE_KEY);

        $console->writeLine('success!', ColorInterface::GREEN);
    }

    /**
     * @param \SpiffyConfig\ConfigManager $configManager
     * @return $this
     */
    public function setConfigManager($configManager)
    {
        $this->configManager = $configManager;
        return $this;
    }

    /**
     * @return \SpiffyConfig\configManager
     */
    public function getConfigManager()
    {
        if (!$this->configManager instanceof configManager) {
            $this->setConfigManager($this->getServiceLocator()->get('SpiffyConfig\ConfigManager'));
        }
        return $this->configManager;
    }

    /**
     * @param \SpiffyConfig\CacheListener $cacheListener
     * @return $this
     */
    public function setCacheListener($cacheListener)
    {
        $this->cacheListener = $cacheListener;
        return $this;
    }

    /**
     * @return \SpiffyConfig\CacheListener
     */
    public function getCacheListener()
    {
        if (!$this->cacheListener instanceof CacheListener) {
            $this->setCacheListener($this->getServiceLocator()->get('SpiffyConfig\CacheListener'));
        }
        return $this->cacheListener;
    }
}