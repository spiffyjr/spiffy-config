<?php

namespace SpiffyConfig\Handler;

use SpiffyConfig\Builder;
use SpiffyConfig\Config;
use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Request as HttpRequest;
use Zend\ServiceManager;

class Runtime extends AbstractListenerAggregate implements
    ServiceManager\ServiceLocatorAwareInterface,
    HandlerInterface
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
     * @var ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(Config\Manager::EVENT_CONFIGURE, array($this, 'configure'));
    }

    /**
     * @param Config\Event $event
     */
    public function configure(Config\Event $event)
    {
        if ($this->serviceLocator->get('Request') instanceof ConsoleRequest) {
            return;
        }

        $resolver = $event->getResolver();
        $builder  = $event->getBuilder();
        $config   = $builder->build($resolver->resolve());

        if ($builder instanceof Builder\Router) {
            if (!isset($config['router']['routes'])) {
                return;
            }

            $router = $this->serviceLocator->get('Router');
            $router->addRoutes($config['router']['routes']);
        } elseif ($builder instanceof Builder\TemplateMap) {
            if (!isset($config['view_manager']['template_map'])) {
                return;
            }

            $templateMap = $this->serviceLocator->get('ViewTemplateMapResolver');
            $templateMap->merge($config['view_manager']['template_map']);
        } else {
            $builderClass = get_class($builder);

            /** @var \SpiffyConfig\Builder\BuilderInterface $builder */
            $map = isset($this->map[$builderClass]) ? $this->map[$builderClass] : null;
            if ($map) {
                if (!isset($config[$map['config_key']])) {
                    return;
                }

                $serviceConfig = new ServiceManager\Config($config[$map['config_key']]);
                $serviceConfig->configureServiceManager($this->serviceLocator->get($map['service_name']));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setServiceLocator(ServiceManager\ServiceLocatorInterface $serviceLocator)
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
