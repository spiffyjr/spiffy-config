<?php

namespace SpiffyConfig\Builder;

use Doctrine\Common\Annotations;
use Doctrine\Common\Cache;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;

abstract class AbstractAnnotationBuilder extends AbstractBuilder
{
    /**
     * @var Annotations\Reader
     */
    protected $annotationReader;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var array Default listeners to register which handle annotations.
     */
    protected $defaultListeners = array();

    /**
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     * @return $this
     */
    public function setAnnotationReader($annotationReader)
    {
        $this->annotationReader = $annotationReader;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Annotations\Reader
     */
    public function getAnnotationReader()
    {
        if (!$this->annotationReader) {
            $this->annotationReader = new Annotations\CachedReader(
                new Annotations\AnnotationReader(),
                new Cache\ArrayCache()
            );
        }
        return $this->annotationReader;
    }

    /**
     * {@inheritDoc}
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
        foreach ($this->defaultListeners as $listener) {
            $eventManager->attach(new $listener);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->setEventManager(new EventManager());
        }
        return $this->eventManager;
    }
}
