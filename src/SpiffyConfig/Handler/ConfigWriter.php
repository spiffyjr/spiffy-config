<?php

namespace SpiffyConfig\Handler;

use SpiffyConfig\Config;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Request as HttpRequest;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class ConfigWriter extends AbstractListenerAggregate implements
    ServiceLocatorAwareInterface,
    HandlerInterface
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(Config\Manager::EVENT_CONFIGURE, array($this, 'configure'));
        $this->listeners[] = $events->attach(Config\Manager::EVENT_CONFIGURE_POST, array($this, 'configurePost'));
    }

    /**
     * @param Config\Event $event
     */
    public function configure(Config\Event $event)
    {
        $resolver     = $event->getResolver();
        $builder      = $event->getBuilder();
        $this->config = ArrayUtils::merge($this->config, $builder->build($resolver->resolve()));
    }

    /**
     * @param EventInterface $event
     */
    public function configurePost(EventInterface $event)
    {
        /** @var \SpiffyConfig\ModuleOptions $options */
        $options = $this->getServiceLocator()->get('SpiffyConfig\ModuleOptions');
        $file    = $options->getAutoloadFile();
        $config  = sprintf('<?php%sreturn %s;%s', PHP_EOL, var_export($this->config, true), PHP_EOL);

        file_put_contents($file, $config);
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