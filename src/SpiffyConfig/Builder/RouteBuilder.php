<?php

namespace SpiffyConfig\Builder;

use ArrayObject;
use ReflectionClass;
use SpiffyConfig\Annotation\Route;
use SpiffyConfig\Annotation\Controller;
use SpiffyConfig\Resolver;
use Zend\Code\Reflection\FileReflection;

class RouteBuilder extends AbstractAnnotationBuilder
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
        'SpiffyConfig\\Builder\\RouteListener',
    );

    /**
     * {@inheritDoc}
     */
    public function build($result)
    {
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        $config = new ArrayObject();
        foreach ($result as $file) {
            $filename = $file->getRealPath();

            if (!in_array($file->getRealPath(), get_included_files())) {
                include $filename;
            }

            try {
                $fileRefl  = new FileReflection($filename);
                $className = $fileRefl->getClass()->getName();
            } catch (\Exception $e) {
                continue;
            }

            $this->buildConfiguration($config, $className);
        }

        return $config->getArrayCopy();
    }

    /**
     * @param ArrayObject $config
     * @param string $className
     */
    protected function buildConfiguration(ArrayObject $config, $className)
    {
        $annotationReader = $this->getAnnotationReader();
        $reflClass        = new ReflectionClass($className);
        $annotations      = $annotationReader->getClassAnnotations($reflClass);

        if ($this->checkForExcludeController($annotations)) {
            return;
        }

        // Special variables set by \SpiffyConfig\Annotation\Controller\RouteParent which appends
        // all children routes with the data in the annotation.
        // This allows for setting defaults at the controller level which is handy for inheritance or traits.
        $routeParentName   = '';
        $routeParentAction = '';

        $controllerName   = $this->discoverControllerName($annotations);
        $controllerName   = $controllerName ?: $className;
        $routeAnnotations = array();

        // Collect controller level annotations
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Controller\RouteParent) {
                $routeParentName   = $annotation->value;
                $routeParentAction = $annotation->action ?: 'index';
            } elseif ($annotation instanceof Route\AbstractRoute) {
                $annotation->controller = $controllerName;
                $routeAnnotations[]     = $annotation;
            }
        }

        // Collection method level annotations
        foreach ($reflClass->getMethods() as $methodRefl) {
            if (!preg_match('/^(.*)Action$/', $methodRefl->getName(), $matches)) {
                continue;
            }

            $actionName = $matches[1];

            foreach ($annotationReader->getMethodAnnotations($methodRefl) as $annotation) {
                if ($annotation instanceof Route\AbstractRoute) {
                    $annotation->controller = $controllerName;
                    $annotation->action     = $annotation->action ? : $actionName;
                    $routeAnnotations[]     = $annotation;
                }
            }
        }

        $routes = array();
        foreach ($routeAnnotations as $annotation) {
            if (!$annotation instanceof Route\AbstractRoute) {
                continue;
            }

            $spec       = new ArrayObject();
            $parentName = $annotation->parent;

            $this->configureRoute($annotation, $className, $spec);

            // Handle SpiffyConfig\Annotation\Controller\RouteParent overrides.
            if ($routeParentName) {
                if ($annotation->action == $routeParentAction) {
                    $annotation->name = $routeParentName;
                } else {
                    $parentName = $routeParentName;
                }
            }

            $routeName = $this->discoverRouteName($annotation);

            if ($parentName) {
                $parts = explode('/', $parentName);
                $root  = array_shift($parts);

                if (!isset($routes[$root])) {
                    $routes[$root] = array();
                }

                $partSpec   = &$routes[$root];
                foreach ($parts as $part) {
                    if (!isset($partSpec['child_routes'][$part])) {
                        $partSpec['child_routes'][$part] = array();
                    }
                    $partSpec = &$partSpec['child_routes'][$part];
                }

                $partSpec['child_routes'][$routeName] = $spec->getArrayCopy();
            } else {
                $routes[$routeName] = $spec->getArrayCopy();
            }
        }

        if (!isset($config['router']['routes'])) {
            $config['router']['routes'] = array();
        }

        $config['router']['routes'] = array_merge($config['router']['routes'], $routes);
    }

    /**
     * @param $annotations
     * @return bool
     */
    protected function checkForExcludeController($annotations)
    {
        $params   = array('annotations' => $annotations);
        $callback = function ($r) {
            return (true === $r);
        };
        $results = $this->getEventManager()->trigger(__FUNCTION__, $this, $params, $callback);

        return (bool) $results->last();
    }

    /**
     * @param $annotations
     * @return mixed|null
     */
    protected function discoverActionName($annotations)
    {
        $params   = array('annotations' => $annotations);
        $callback = function ($r) {
            return (true === $r);
        };
        $results = $this->getEventManager()->trigger(__FUNCTION__, $this, $params, $callback);

        return is_string($results->last()) ? $results->last() : null;
    }

    /**
     * @param $annotations
     * @return mixed|null
     */
    protected function discoverControllerName($annotations)
    {
        $params   = array('annotations' => $annotations);
        $callback = function ($r) {
            return (true === $r);
        };
        $results = $this->getEventManager()->trigger(__FUNCTION__, $this, $params, $callback);

        return is_string($results->last()) ? $results->last() : null;
    }

    /**
     * @param $annotation
     * @param string $className
     * @param ArrayObject $spec
     */
    protected function configureRoute($annotation, $className, ArrayObject $spec)
    {
        $params = array(
            'annotation' => $annotation,
            'className'  => $className,
            'spec'       => $spec
        );

        $this->getEventManager()->trigger(__FUNCTION__, $this, $params);
    }

    /**
     * @param $annotation
     * @return mixed|null
     */
    protected function discoverRouteName($annotation)
    {
        $params   = array('annotation' => $annotation,);
        $callback = function ($r) {
            return (true === $r);
        };
        $results  = $this->getEventManager()->trigger(__FUNCTION__, $this, $params, $callback);

        return is_string($results->last()) ? $results->last() : null;
    }

    /**
     * @param ArrayObject $config
     * @throws \RuntimeException
     */
    protected function assembleRoutes(ArrayObject $config)
    {
        if (!isset($config['router'])) {
            return;
        }
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
}
