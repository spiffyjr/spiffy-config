<?php

namespace SpiffyConfig\Builder\Router;

use SpiffyConfig\Annotation\Route;
use SpiffyConfig\Builder\AbstractRouter;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

class DefaultListener extends AbstractListenerAggregate
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
        $this->listeners[] = $events->attach('configureRoute', array($this, 'handleDefaults'));
        $this->listeners[] = $events->attach('configureRoute', array($this, 'handleExtras'));
        $this->listeners[] = $events->attach('configureRoute', array($this, 'handleRoute'));
        $this->listeners[] = $events->attach('configureRoute', array($this, 'handleType'));
        $this->listeners[] = $events->attach('discoverRouteName', array($this, 'discoverRouteName'));

        $this->listeners[] = $events->attach('configureController', array($this, 'handleRoot'));
    }

    /**
     * @param EventInterface $event
     */
    public function handleRoot(EventInterface $event)
    {
        $annotation = $event->getParam('annotation');
        if (!$annotation instanceof Route\Root) {
            return;
        }

        $controllerSpec              = $event->getParam('controllerSpec');
        $controllerSpec['rootRoute'] = $annotation->value;
        $controllerSpec['rootName']  = $annotation->name;
    }

    /**
     * @param EventInterface $event
     * @return string
     */
    public function discoverRouteName(EventInterface $event)
    {
        $annotation     = $event->getParam('annotation');
        $controllerSpec = $event->getParam('controllerSpec');

        if ($annotation instanceof Route\AbstractType && null !== $annotation->name) {
            $parts = array();

            if (isset($controllerSpec['rootName'])) {
                $parts[] = $controllerSpec['rootName'];
            }
            if (!empty($annotation->name)) {
                $parts[] = $annotation->name;
            }
            return implode(AbstractRouter::CHILD_ROUTE_SEPARATOR, $parts);
        }

        $routeSpec = $event->getParam('routeSpec');

        $parts = array(
            $this->canonicalize($controllerSpec['name']),
            $this->canonicalize($routeSpec['actionName'])
        );

        return implode('_', $parts);
    }

    /**
     * @param EventInterface $event
     */
    public function handleDefaults(EventInterface $event)
    {
        $routeSpec      = $event->getParam('routeSpec');
        $controllerSpec = $event->getParam('controllerSpec');

        $defaults = array(
            'controller' => $controllerSpec['name'],
            'action'     => $routeSpec['actionName']
        );

        $routeSpec['options']['defaults'] = $defaults;
    }

    /**
     * @param EventInterface $event
     */
    public function handleExtras(EventInterface $event)
    {
        $annotation = $event->getParam('annotation');
        if (!$annotation instanceof Route\AbstractType) {
            return;
        }

        $skip       = array('name', 'routeKey', $annotation->routeKey, 'type', 'value');
        $routeSpec  = $event->getParam('routeSpec');
        foreach ($annotation as $key => $value) {
            if (in_array($key, $skip)) {
                continue;
            }
            $routeSpec['options'][$key] = $value;
        }
    }

    /**
     * @param EventInterface $event
     */
    public function handleRoute(EventInterface $event)
    {
        $annotation = $event->getParam('annotation');
        if (!$annotation instanceof Route\AbstractType) {
            return;
        }

        $controllerSpec = $event->getParam('controllerSpec');
        $routeSpec      = $event->getParam('routeSpec');

        $route = $annotation->value;
        if (isset($controllerSpec['rootRoute']) && empty($route)) {
            $route = $controllerSpec['rootRoute'];
        }

        $routeSpec['options'][$annotation->routeKey] = $route;
    }

    /**
     * @param EventInterface $event
     */
    public function handleType(EventInterface $event)
    {
        $annotation = $event->getParam('annotation');
        if (!$annotation instanceof Route\AbstractType) {
            return;
        }

        $routeSpec         = $event->getParam('routeSpec');
        $routeSpec['type'] = $annotation->type;
    }

    protected function canonicalize($name)
    {
        return strtolower(strtr($name, $this->canonicalNamesReplacements));
    }
}