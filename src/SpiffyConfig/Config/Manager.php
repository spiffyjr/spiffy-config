<?php

namespace SpiffyConfig\Config;

use SpiffyConfig\Builder;
use SpiffyConfig\Handler;
use SpiffyConfig\Resolver;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceManager;

class Manager extends ServiceManager
{
    const EVENT_CONFIGURE      = 'configure';
    const EVENT_CONFIGURE_POST = 'configure.post';

    /**
     * @var \SpiffyConfig\Config\Collection[]
     */
    protected $collections = array();

    /**
     * @var Builder\Manager
     */
    protected $builderManager;

    /**
     * @var Resolver\Manager
     */
    protected $resolverManager;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->eventManager = new EventManager();
    }

    /**
     * @param Handler\HandlerInterface $handler
     * @return $this
     */
    public function addHandler(Handler\HandlerInterface $handler)
    {
        $this->eventManager->attach($handler);
        return $this;
    }

    /**
     * @param \SpiffyConfig\Resolver\Manager $resolverManager
     * @return $this
     */
    public function setResolverManager(Resolver\Manager $resolverManager)
    {
        $this->resolverManager = $resolverManager;
        return $this;
    }

    /**
     * @return \SpiffyConfig\Resolver\Manager
     */
    public function getResolverManager()
    {
        if (!$this->resolverManager instanceof Resolver\Manager) {
            $this->setResolverManager(new Resolver\Manager());
        }
        return $this->resolverManager;
    }

    /**
     * @param \SpiffyConfig\Builder\Manager $builderManager
     * @return $this
     */
    public function setBuilderManager(Builder\Manager $builderManager)
    {
        $this->builderManager = $builderManager;
        return $this;
    }

    /**
     * @return \SpiffyConfig\Builder\Manager
     */
    public function getBuilderManager()
    {
        if (!$this->builderManager instanceof Builder\Manager) {
            $this->setBuilderManager(new Builder\Manager());
        }
        return $this->builderManager;
    }

    /**
     * Iterate through resolvers and write the result
     *
     * @param string $collectionName
     */
    public function configure($collectionName)
    {
        /** @var \SpiffyConfig\Config\Collection $collection */
        $collection = $this->get($collectionName);
        $event      = new Event();

        foreach ($collection->getConfigs() as $config) {
            $event->setResolver($config->getResolver());

            foreach ($config->getBuilders() as $builder) {
                $event->setBuilder($builder);
                $this->eventManager->trigger(static::EVENT_CONFIGURE, $event);
            }
        }

        $this->eventManager->trigger(static::EVENT_CONFIGURE_POST, $this);
    }
}
