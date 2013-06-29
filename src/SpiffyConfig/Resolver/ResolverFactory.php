<?php

namespace SpiffyConfig\Resolver;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ResolverFactory implements FactoryInterface
{
    /**
     * @var array
     */
    protected $spec = array();

    /**
     * @param array $spec
     * @return $this
     */
    public function setSpec($spec)
    {
        $this->spec = $spec;
        return $this;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ResolverInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $spec     = $this->spec;
        $type     = isset($spec['type']) ? $spec['type'] : null;
        $options  = isset($spec['options']) ? $spec['options'] : array();
        $builders = isset($spec['builders']) ? $spec['builders'] : array();

        if (!is_string($type) || empty($type)) {
            // todo: throw exception
            echo 'missing type';
            exit;
        }

        /** @var ResolverInterface $resolver */
        $resolver = new $type($options);

        foreach ($builders as $builder) {
            if (is_string($builder)) {
                if ($serviceLocator->has($builder)) {
                    $builder = $serviceLocator->get($builder);
                } else {
                    $builder = new $builder;
                }
            }
            $resolver->addBuilder($builder);
        }

        return $resolver;
    }
}
