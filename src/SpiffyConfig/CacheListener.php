<?php

namespace SpiffyConfig;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Request as HttpRequest;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class CacheListener extends AbstractListenerAggregate implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('configure', array($this, 'buildCache'));
    }

    /**
     * @param EventInterface $e
     */
    public function buildCache(EventInterface $e)
    {
        $resolvers = $e->getTarget();
        $config    = array();

        /** @var \SpiffyConfig\Resolver\ResolverInterface $resolver */
        foreach ($resolvers as $resolver) {
            /** @var \SpiffyConfig\Builder\BuilderInterface $builder */
            foreach ($resolver->getBuilders() as $builder) {
                $config = ArrayUtils::merge($config, $builder->build($resolver));
            }
        }

        /** @var \SpiffyConfig\ModuleOptions $options */
        $options = $this->getServiceLocator()->get('SpiffyConfig\ModuleOptions');
        $file    = $options->getAutoloadFile();
        $config  = sprintf('<?%sreturn %s;%s', PHP_EOL, var_export($config, true), PHP_EOL);

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