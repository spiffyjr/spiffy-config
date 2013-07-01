<?php

namespace SpiffyConfig;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Request as HttpRequest;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RuntimeListener extends AbstractListenerAggregate implements ServiceLocatorAwareInterface
{
    /**
     * @var array
     */
    protected $map = array(
        'SpiffyConfig\Builder\ControllerLoader' => array(
            'service_name' => 'ControllerLoader',
            'config_key'   => 'controllers'
        )
    );

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('configure', array($this, 'configureRouters'));
        $this->listeners[] = $events->attach('configure', array($this, 'configureServiceLocators'));
        $this->listeners[] = $events->attach('configure', array($this, 'configureTemplateMap'));
    }

    /**
     * @param EventInterface $e
     */
    public function configureRouters(EventInterface $e)
    {
        if (!$this->serviceLocator->get('Request') instanceof HttpRequest) {
            return;
        }

        $router   = $this->serviceLocator->get('Router');
        $resolvers = $e->getTarget();

        /** @var \SpiffyConfig\Resolver\ResolverInterface $resolver */
        foreach ($resolvers as $resolver) {
            foreach ($resolver->getBuilders() as $builder) {
                if ($builder instanceof Builder\Router) {
                    $config = $builder->build($resolver);
                    if (!isset($config['router']['routes'])) {
                        continue;
                    }

                    $router->addRoutes($config['router']['routes']);
                }
            }
        }
    }

    /**
     * @param EventInterface $e
     */
    public function configureServiceLocators(EventInterface $e)
    {
        $resolvers = $e->getTarget();

        /** @var \SpiffyConfig\Resolver\ResolverInterface $resolver */
        foreach ($resolvers as $resolver) {
            foreach ($resolver->getBuilders() as $builder) {
                $builderClass = get_class($builder);

                /** @var \SpiffyConfig\Builder\BuilderInterface $builder */
                $map = isset($this->map[$builderClass]) ? $this->map[$builderClass] : null;
                if ($map) {
                    $config = $builder->build();

                    if (!isset($config[$map['config_key']])) {
                        continue;
                    }

                    $config = new Config($config[$map['config_key']]);
                    $config->configureServiceManager($this->serviceLocator->get($map['service_name']));
                }
            }
        }
    }

    /**
     * @param EventInterface $e
     */
    public function configureTemplateMap(EventInterface $e)
    {
        $resolvers = $e->getTarget();

        /** @var \SpiffyConfig\Resolver\ResolverInterface $resolver */
        foreach ($resolvers as $resolver) {
            foreach ($resolver->getBuilders() as $builder) {
                if ($builder instanceof Builder\TemplateMap) {
                    $config = $builder->build();

                    if (!isset($config['view_manager']['template_map'])) {
                        continue;
                    }

                    $templateMap = $this->serviceLocator->get('ViewTemplateMapResolver');
                    $templateMap->merge($config['view_manager']['template_map']);
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}