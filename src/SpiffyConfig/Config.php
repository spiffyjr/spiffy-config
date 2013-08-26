<?php

namespace SpiffyConfig;

class Config
{
    /**
     * @var Builder\BuilderInterface[]
     */
    protected $builders;

    /**
     * @var Resolver\ResolverInterface
     */
    protected $resolver;

    /**
     * @param Resolver\ResolverInterface $resolver
     */
    public function __construct(Resolver\ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @return Resolver\ResolverInterface
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param Builder\BuilderInterface $builder
     * @return $this
     */
    public function addBuilder(Builder\BuilderInterface $builder)
    {
        $this->builders[] = $builder;
        return $this;
    }

    /**
     * @return Builder\BuilderInterface[]
     */
    public function getBuilders()
    {
        return $this->builders;
    }

    /**
     * @return array
     */
    public function getMergedConfig()
    {
        $config = array();
        $result = $this->resolver->resolve();

        foreach ($this->builders as $builder) {
            $config = array_merge_recursive($config, $builder->build($result));
        }
        return $config;
    }
}