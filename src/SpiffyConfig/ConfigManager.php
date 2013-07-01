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
    protected $resolvers = array();

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @param ResolverInterface $resolver
     * @return $this
     */
    public function addResolver(ResolverInterface $resolver)
    {
        $this->resolvers[] = $resolver;
        return $this;
    }

    /**
     * Iterate through resolves and and fire the 'configure' event on each.
     *
     * @triggers configure
     */
    public function configure()
    {
        $this->getEventManager()->trigger('configure', $this->resolvers);
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