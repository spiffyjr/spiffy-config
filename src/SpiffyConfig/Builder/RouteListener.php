<?php

namespace SpiffyConfig\Builder;

use SpiffyConfig\Annotation\Route;
use SpiffyConfig\Annotation\Service;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

class RouteListener extends AbstractListenerAggregate
{
    /**
     * @var array
     */
    protected $canonicalNamesReplacements = array('-' => '_', ' ' => '_', '\\' => '_', '/' => '_');

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('configureRoute', array($this, 'onHandleMayTerminate'));
        $this->listeners[] = $events->attach('configureRoute', array($this, 'onHandleOptions'));
        $this->listeners[] = $events->attach('configureRoute', array($this, 'onHandleRoute'));
        $this->listeners[] = $events->attach('configureRoute', array($this, 'onHandleType'));

        $this->listeners[] = $events->attach('discoverControllerName', array($this, 'onDiscoverControllerName'));
        $this->listeners[] = $events->attach('discoverRouteName', array($this, 'onDiscoverRouteName'));
    }

    /**
     * @param EventInterface $e
     * @return null|string
     */
    public function onDiscoverControllerName(EventInterface $e)
    {
        $annotations = $e->getParam('annotations');

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Service\AbstractService) {
                return $annotation->name;
            }
        }
        return null;
    }

    /**
     * @param EventInterface $event
     * @return string
     */
    public function onDiscoverRouteName(EventInterface $event)
    {
        $annotation = $event->getParam('annotation');

        if ($annotation instanceof Route\AbstractRoute && $annotation->name) {
            return $annotation->name;
        }

        $parts = array(
            $this->canonicalize($annotation->controller),
            $this->canonicalize($annotation->action)
        );

        return implode('.', $parts);
    }

    /**
     * @param EventInterface $event
     * @throws \RuntimeException
     */
    public function onHandleMayTerminate(EventInterface $event)
    {
        $annotation = $event->getParam('annotation');
        if (!$annotation instanceof Route\AbstractRoute) {
            return;
        }

        if (!$annotation->mayTerminate) {
            return;
        }

        $event->getParam('spec')['may_terminate'] = $annotation->mayTerminate;
    }

    /**
     * @param EventInterface $event
     */
    public function onHandleOptions(EventInterface $event)
    {
        $annotation = $event->getParam('annotation');
        if (!$annotation instanceof Route\AbstractRoute) {
            return;
        }

        $spec            = $event->getParam('spec');
        $spec['options'] = isset($spec['options']) ? $spec['options'] : [];

        $options                           = $annotation->options;
        $options['defaults']['controller'] = $annotation->controller;
        $options['defaults']['action']     = $annotation->action;

        $spec['options'] = array_merge($spec['options'], $options);
    }

    /**
     * @param EventInterface $event
     */
    public function onHandleRoute(EventInterface $event)
    {
        $annotation = $event->getParam('annotation');
        if (!$annotation instanceof Route\AbstractRoute) {
            return;
        }

        $key = null;
        if ($annotation instanceof Route\Generic) {
            ; // intentionally left blank
        } else if ($annotation instanceof Route\Regex) {
            $key = 'regex';
        } else {
            $key = 'route';
        }

        if ($key && $annotation->value) {
            $event->getParam('spec')['options'][$key] = $annotation->value;
        }
    }

    /**
     * @param EventInterface $event
     * @throws \RuntimeException
     */
    public function onHandleType(EventInterface $event)
    {
        $annotation = $event->getParam('annotation');
        if (!$annotation instanceof Route\AbstractRoute) {
            return;
        }

        if (!$annotation->type) {
            $className = $event->getParam('className');
            throw new \RuntimeException(sprintf(
                'Missing type for route "%s" defined in "%s"',
                get_class($annotation),
                $className
            ));
        }

        $event->getParam('spec')['type'] = $annotation->type;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function canonicalize($name)
    {
        return strtolower(strtr($name, $this->canonicalNamesReplacements));
    }
}