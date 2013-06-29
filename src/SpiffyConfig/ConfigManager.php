<?php

namespace SpiffyConfig;

use SpiffyConfig\Resolver;
use SpiffyConfig\Resolver\ResolverInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class ConfigManager implements EventManagerAwareInterface
{
    /**
     * @var array
     */
    protected $resolvers;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var ModuleOptions
     */
    protected $options;

    public function addResolver(ResolverInterface $resolver)
    {
        $this->resolvers[] = $resolver;
        return $this;
    }

    public function configure()
    {
        foreach ($this->resolvers as $resolver) {
            $this->getEventManager()->trigger('configure', $resolver);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventManager()
    {
        if (!$this->eventManager instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->eventManager;
    }
}