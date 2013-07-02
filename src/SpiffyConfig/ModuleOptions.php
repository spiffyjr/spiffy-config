<?php

namespace SpiffyConfig;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * The name of the file that's generated via the CLI for production usage. This file
     * needs to be available from your glob_path in application.config.php so it's automatically
     * merged with your application configuration at runtime.
     *
     * @var string
     */
    protected $autoloadFile = 'config/autoload/spiffyconfig.local.php';

    /**
     * The collection to be ran during runtime when enabled is true.
     *
     * @var string
     */
    protected $runtimeCollection = 'default';

    /**
     * The collection to be ran when generating the autoload from CLI.
     *
     * @var string
     */
    protected $cliCollection = 'default';

    /**
     * Whether or not the module is enabled. You want this to be `true` during development so that
     * configurations can be generated at runtime. On production you should generate the autoload file
     * and set this to false for performance.
     *
     * @var bool
     */
    protected $enabled = true;

    /**
     * A service manager configuration of builders to be registered with the builder manager.
     * An additional `factory_config` key is used to create builders from an array.
     *
     * @var array
     */
    protected $builders = array();

    /**
     * A service manager configuration of collections to be registered with the config manager.
     * An additional `factory_config` key is used to create collections from an array.
     *
     * @var array
     */
    protected $collections = array();

    /**
     * An array of handlers to be registered for handling the configuration returned by collections.
     * Each key can be the FQCN or a service manager alias.
     *
     * @var array
     */
    protected $handlers = array();

    /**
     * A service manager configuration of resolvers to be registered with the resolver manager.
     * An additional `factory_config` key is used to create resolvers from an array.
     *
     * @var array
     */
    protected $resolvers = array();

    /**
     * @param string $autoloadFile
     * @return $this
     */
    public function setAutoloadFile($autoloadFile)
    {
        $this->autoloadFile = $autoloadFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getAutoloadFile()
    {
        return $this->autoloadFile;
    }

    /**
     * @param array $builders
     * @return $this
     */
    public function setBuilders($builders)
    {
        $this->builders = $builders;
        return $this;
    }

    /**
     * @return array
     */
    public function getBuilders()
    {
        return $this->builders;
    }

    /**
     * @param boolean $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param array $resolvers
     * @return $this
     */
    public function setResolvers($resolvers)
    {
        $this->resolvers = $resolvers;
        return $this;
    }

    /**
     * @return array
     */
    public function getResolvers()
    {
        return $this->resolvers;
    }

    /**
     * @param array $collections
     * @return $this
     */
    public function setCollections($collections)
    {
        $this->collections = $collections;
        return $this;
    }

    /**
     * @return array
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * @param array $handlers
     * @return $this
     */
    public function setHandlers($handlers)
    {
        $this->handlers = $handlers;
        return $this;
    }

    /**
     * @return array
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * @param string $runtimeCollection
     * @return $this
     */
    public function setRuntimeCollection($runtimeCollection)
    {
        $this->runtimeCollection = $runtimeCollection;
        return $this;
    }

    /**
     * @return string
     */
    public function getRuntimeCollection()
    {
        return $this->runtimeCollection;
    }

    /**
     * @param string $cliCollection
     * @return $this
     */
    public function setCliCollection($cliCollection)
    {
        $this->cliCollection = $cliCollection;
        return $this;
    }

    /**
     * @return string
     */
    public function getCliCollection()
    {
        return $this->cliCollection;
    }
}
