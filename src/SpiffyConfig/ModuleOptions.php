<?php

namespace SpiffyConfig;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * Whether or not the module is enabled. You want this to be `true` during development so that
     * configurations can be generated at runtime. On production you should generate the cache file
     * and set this to false for performance.
     *
     * @var bool
     */
    protected $enabled = true;

    /**
     * The name of the file that's generated via the CLI for production usage. This file
     * needs to be available from your glob_path in application.config.php so it's automatically
     * merged with your application configuration at runtime.
     *
     * @var string
     */
    protected $cacheFile = 'config/autoload/spiffyconfig.local.php';

    /**
     * The collections to build and merge during runtime.
     *
     * @var string
     */
    protected $runtimeCollections = array('default');

    /**
     * The collections to use when building the cache.
     *
     * @var string
     */
    protected $cacheCollections = array('default');

    /**
     * Builders work on resolvers to create the configuration files.
     *
     * @var array
     */
    protected $builders = array();

    /**
     * Resolvers locate files or information for builders to process.
     *
     * @var array
     */
    protected $resolvers = array();

    /**
     * Collections are sets of builders identified by a name.
     *
     * @var array
     */
    protected $collections = array();

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
     * @param string $cacheCollections
     * @return $this
     */
    public function setCacheCollections($cacheCollections)
    {
        $this->cacheCollections = $cacheCollections;
        return $this;
    }

    /**
     * @return string
     */
    public function getCacheCollections()
    {
        return $this->cacheCollections;
    }

    /**
     * @param string $cacheFile
     * @return $this
     */
    public function setCacheFile($cacheFile)
    {
        $this->cacheFile = $cacheFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getCacheFile()
    {
        return $this->cacheFile;
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
     * @param string $runtimeCollections
     * @return $this
     */
    public function setRuntimeCollections($runtimeCollections)
    {
        $this->runtimeCollections = $runtimeCollections;
        return $this;
    }

    /**
     * @return string
     */
    public function getRuntimeCollections()
    {
        return $this->runtimeCollections;
    }
}