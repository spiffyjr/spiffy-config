<?php

namespace SpiffyConfig;

use Zend\ServiceManager\Exception;

class ConfigManager
{
    const EVENT_CONFIGURE      = 'configure';
    const EVENT_CONFIGURE_POST = 'configure.post';

    /**
     * @var Builder\Factory
     */
    protected $builderFactory;

    /**
     * @var Resolver\Factory
     */
    protected $resolverFactory;

    /**
     * @var Builder\BuilderInterface
     */
    protected $builders = array();

    /**
     * @var ConfigCollection[]
     */
    protected $collections = array();

    /**
     * @var Resolver\ResolverInterface[]
     */
    protected $resolvers = array();

    /**
     * Constructor.
     */
    public function __construct(array $spec = array())
    {
        $this->builderFactory  = new Builder\Factory();
        $this->resolverFactory = new Resolver\Factory();
    }

    /**
     * @param array $config
     * @return ConfigManager
     */
    public static function create(array $config)
    {
        $manager = new ConfigManager();
        if (isset($config['builders'])) {
            foreach ($config['builders'] as $name => $spec) {
                $manager->addBuilder($name, $spec);
            }
        }

        if (isset($config['resolvers'])) {
            foreach ($config['resolvers'] as $name => $spec) {
                $manager->addResolver($name, $spec);
            }
        }

        if (isset($config['collections'])) {
            foreach ($config['collections'] as $name => $spec) {
                $manager->addCollection($name, $spec);
            }
        }

        return $manager;
    }

    /**
     * @param string $name
     * @param array|ConfigCollection $spec
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function addCollection($name, $spec)
    {
        if (isset($this->collections[$name])) {
            throw new \RuntimeException(sprintf(
                'collection with name "%s" already exists',
                $name
            ));
        }

        $collection = null;
        if (is_array($spec)) {
            $collection = $this->createCollection($spec);
        }

        if (!$collection instanceof ConfigCollection) {
            throw new \InvalidArgumentException('invalid collection');
        }

        $this->collections[$name] = $collection;
        return $this;
    }

    /**
     * @param string $name
     * @return ConfigCollection
     * @throws \RuntimeException
     */
    public function getCollection($name)
    {
        if (!isset($this->collections[$name])) {
            throw new \RuntimeException(sprintf(
                'collection with name "%s" does not exist',
                $name
            ));
        }
        return $this->collections[$name];
    }

    /**
     * @param array $spec
     * @return ConfigCollection
     */
    public function createCollection(array $spec)
    {
        $collection = new ConfigCollection();
        foreach ($spec as $resolver => $builders) {
            $config = new Config($this->getResolver($resolver));

            foreach ($builders as $builder) {
                $config->addBuilder($this->getBuilder($builder));
            }
            $collection->addConfig($config);
        }

        return $collection;
    }

    /**
     * @param string $name
     * @param array|Builder\BuilderInterface $spec
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function addBuilder($name, $spec)
    {
        if (isset($this->builders[$name])) {
            throw new \RuntimeException(sprintf(
                'builder with name "%s" already exists',
                $name
            ));
        }
        $builder = null;
        if (is_array($spec)) {
            $builder = $this->builderFactory->create($spec);
        }

        if (!$builder instanceof Builder\BuilderInterface) {
            throw new \InvalidArgumentException('invalid builder');
        }

        $this->builders[$name] = $builder;
        return $this;
    }

    /**
     * @param string $name
     * @return Builder\BuilderInterface
     * @throws \RuntimeException
     */
    public function getBuilder($name)
    {
        if (!isset($this->builders[$name])) {
            throw new \RuntimeException(sprintf(
                'builder with name "%s" does not exist',
                $name
            ));
        }
        return $this->builders[$name];
    }

    /**
     * @param string $name
     * @param array|Resolver\ResolverInterface $spec
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function addResolver($name, $spec)
    {
        if (isset($this->resolvers[$name])) {
            throw new \RuntimeException(sprintf(
                'resolver with name "%s" already exists',
                $name
            ));
        }
        $resolver = null;
        if (is_array($spec)) {
            $resolver = $this->resolverFactory->create($spec);
        }

        if (!$resolver instanceof Resolver\ResolverInterface) {
            throw new \InvalidArgumentException('invalid resolver');
        }

        $this->resolvers[$name] = $resolver;
        return $this;
    }

    /**
     * @param string $name
     * @return Resolver\ResolverInterface
     * @throws \RuntimeException
     */
    public function getResolver($name)
    {
        if (!isset($this->resolvers[$name])) {
            throw new \RuntimeException(sprintf(
                'resolver with name "%s" does not exist',
                $name
            ));
        }
        return $this->resolvers[$name];
    }

    /**
     * Iterate through resolvers and write the result
     *
     * @param string $name
     * @return array
     */
    public function configure($name)
    {
        /** @var ConfigCollection $collection */
        $collection   = $this->getCollection($name);
        $mergedConfig = array();

        foreach ($collection->getConfigs() as $config) {
            $mergedConfig = array_merge_recursive($mergedConfig, $config->getMergedConfig());
        }
        return $mergedConfig;
    }
}
