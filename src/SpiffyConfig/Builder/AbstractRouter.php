<?php

namespace SpiffyConfig\Builder;

use ArrayObject;
use Doctrine\Common\Annotations\AnnotationRegistry;
use ReflectionClass;
use SpiffyConfig\Annotation\Route;

abstract class AbstractRouter extends AbstractAnnotationBuilder
{
    const CHILD_ROUTE_SEPARATOR = '/';

    /**
     * @var array
     */
    protected $canonicalNamesReplacements = array('-' => '_', ' ' => '_', '\\' => '_', '/' => '_');

    /**
     * @var array
     */
    protected $defaultListeners = array(
        'SpiffyConfig\\Builder\\Router\\DefaultListener',
    );

    /**
     * @param ArrayObject $config
     * @param ReflectionClass $classRefl
     * @param string $defaultControllerName
     */
    protected function buildConfiguration(
        ArrayObject $config,
        ReflectionClass $classRefl,
        $defaultControllerName
    ) {
        $controllerSpec   = new ArrayObject();
        $annotationReader = $this->getAnnotationReader();
        $annotations      = $annotationReader->getClassAnnotations($classRefl);

        $this->discoverControllerName($annotations, $controllerSpec);
        $this->configureController($annotations, $controllerSpec);

        if ($this->checkForExcludeController($annotations)) {
            return;
        }

        if (!isset($controllerSpec['name'])) {
            $controllerSpec['name'] = $defaultControllerName;
        }

        foreach ($classRefl->getMethods() as $methodRefl) {
            $actionName = $this->getActionName($methodRefl->getName());
            if (!$actionName) {
                continue;
            }

            $annotations = $annotationReader->getMethodAnnotations($methodRefl);

            foreach ($annotations as $annotation) {
                $routeSpec               = new ArrayObject();
                $routeSpec['actionName'] = $actionName;

                $this->configureRoute($annotation, $controllerSpec, $routeSpec);

                if (isset($routeSpec['type'])) {
                    $routeName = $this->discoverRouteName($annotation, $controllerSpec, $routeSpec);
                    if ($routeName) {
                        $config['router']['routes'][$routeName] = $routeSpec->getArrayCopy();
                    }
                }
            }
        }
    }

    protected function checkForExcludeController($annotations)
    {
        $params   = array('annotations' => $annotations);
        $callback = function ($r) {
            return (true === $r);
        };
        $results = $this->getEventManager()->trigger(__FUNCTION__, $this, $params, $callback);

        return (bool) $results->last();
    }

    protected function configureController($annotations, ArrayObject $controllerSpec)
    {
        if ($this->checkForExcludeController($annotations, $controllerSpec)) {
            return;
        }

        $eventManager = $this->getEventManager();
        foreach ($annotations as $annotation) {
            $eventManager->trigger(
                __FUNCTION__,
                $this,
                array(
                    'annotation'     => $annotation,
                    'controllerSpec' => $controllerSpec
                )
            );
        }
    }

    protected function discoverControllerName($annotations, ArrayObject $controllerSpec)
    {
        $params   = array('annotations' => $annotations, 'controllerSpec' => $controllerSpec);
        $callback = function ($r) {
            return (true === $r);
        };
        $results = $this->getEventManager()->trigger(__FUNCTION__, $this, $params, $callback);

        return is_string($results->last()) ? $results->last() : null;
    }

    protected function checkForExcludeRoute($annotation, ArrayObject $controllerSpec, ArrayObject $routeSpec)
    {
        $params = array(
            'annotation'     => $annotation,
            'controllerSpec' => $controllerSpec,
            'routeSpec'      => $routeSpec
        );
        $callback = function ($r) {
            return (true === $r);
        };
        $results = $this->getEventManager()->trigger(__FUNCTION__, $this, $params, $callback);

        return (bool) $results->last();
    }

    protected function configureRoute($annotation, ArrayObject $controllerSpec, ArrayObject $routeSpec)
    {
        if ($this->checkForExcludeRoute($annotation, $controllerSpec, $routeSpec)) {
            return;
        }

        $params = array(
            'annotation'     => $annotation,
            'controllerSpec' => $controllerSpec,
            'routeSpec'      => $routeSpec
        );

        $this->getEventManager()->trigger(__FUNCTION__, $this, $params);
    }

    protected function discoverRouteName($annotation, ArrayObject $controllerSpec, ArrayObject $routeSpec)
    {
        $params = array(
            'annotation'     => $annotation,
            'controllerSpec' => $controllerSpec,
            'routeSpec'      => $routeSpec
        );
        $callback = function ($r) {
            return (true === $r);
        };

        $results = $this->getEventManager()->trigger(__FUNCTION__, $this, $params, $callback);

        return is_string($results->last()) ? $results->last() : null;
    }

    protected function assembleRoutes(ArrayObject $config)
    {
        if (!isset($config['router']['routes'])) {
            return;
        }

        $routes = $config['router']['routes'];
        foreach ($routes as $routeName => $route) {
            $parts = explode(static::CHILD_ROUTE_SEPARATOR, $routeName);

            if (2 > count($parts)) {
                continue;
            }

            $parent = $parts[0];
            $child  = $parts[1];

            if (!isset($routes[$parent])) {
                throw new \RuntimeException(
                    sprintf('missing parent route "%s" for child "%s"', $parent, $child)
                );
            }

            $routes[$parent]['may_terminate']        = true;
            $routes[$parent]['child_routes'][$child] = $route;

            unset($routes[$routeName]);
        }

        $config['router']['routes'] = $routes;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function canonicalize($name)
    {
        return strtolower(strtr($name, $this->canonicalNamesReplacements));
    }

    /**
     * @param string $input
     * @return string|null
     */
    protected function getActionName($input)
    {
        if (preg_match('/^(.*)Action$/', $input, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
