<?php

namespace SpiffyConfig\Resolver;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ResolverFactory implements FactoryInterface
{
    /**
     * @var array
     */
    protected $map = array(
        'file' => 'SpiffyConfig\Resolver\File',
    );

    /**
     * @var array
     */
    protected $spec = array();

    /**
     * @param array $spec
     */
    public function __construct(array $spec)
    {
        $this->spec = $spec;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ResolverInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $resolver = isset($this->spec['type']) ? $this->spec['type'] : null;
        $options  = isset($this->spec['options']) ? $this->spec['options'] : array();

        if (!$resolver) {
            // todo: throw exception
            echo 'missing type for resolver';
        }

        if (is_string($resolver)) {
            $mapName = strtolower(trim($resolver));

            if ($serviceLocator->has($resolver)) {
                $resolver = $serviceLocator->get($resolver);
            } elseif (isset($this->map[$mapName])) {
                $resolver = new $this->map[$mapName]($options);
            } else {
                $resolver = new $resolver($options);
            }
        }

        return $resolver;
    }
}
