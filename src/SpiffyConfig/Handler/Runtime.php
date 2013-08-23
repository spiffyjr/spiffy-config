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

        if ($builder instanceof Builder\RouteBuilder) {
            if (!isset($config['router']['routes'])) {
                return;
            }

            $router = $this->serviceLocator->get('Router');
            $router->addRoutes($config['router']['routes']);
        } else if ($builder instanceof Builder\AbstractServiceManager) {
            if (isset($config['controllers'])) {
                /** @var \Zend\Mvc\Controller\ControllerManager $loader */
                $serviceConfig = new ServiceManager\Config($config['controllers']);
                $serviceConfig->configureServiceManager($this->getServiceLocator()->get('ControllerLoader'));
            }
        } else if ($builder instanceof Builder\TemplateMapBuilder) {
            if (!isset($config['view_manager']['template_map'])) {
                return;
            }

            /** @var \Zend\View\Resolver\TemplateMapResolver $templateMap */
            $templateMap = $this->serviceLocator->get('ViewTemplateMapResolver');

            // Twig autoloading
            if ($this->getServiceLocator()->has('ZfcTwigLoaderTemplateMap')) {
                /** @var \ZfcTwig\Twig\MapLoader $twigLoader */
                $twigLoader = $this->getServiceLocator()->get('ZfcTwigLoaderTemplateMap');

                /** @var \ZfcTwig\Twig\StackLoader $twigStack*/
                $twigStack  = $this->getServiceLocator()->get('ZfcTwigLoaderTemplatePathStack');

                foreach ($config['view_manager']['template_map'] as $name => $path) {
                    if (strstr($path, $twigStack->getDefaultSuffix())) {
                        $twigLoader->add($name, $path);
                    } else {
                        $templateMap->add($name, $path);
                    }
                }
            } else {
                $templateMap->merge($config['view_manager']['template_map']);
            }
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
