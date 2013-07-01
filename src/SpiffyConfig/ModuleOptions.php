<?php

namespace SpiffyConfig;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var bool
     */
    protected $enableProduction = true;

    /**
     * @var string
     */
    protected $autoloadFile;

    /**
     * @var array
     */
    protected $configListeners = array();

    /**
     * @var array
     */
    protected $resolvers = array();

    /**
     * @param boolean $enableProduction
     * @return $this
     */
    public function setEnableProduction($enableProduction)
    {
        $this->enableProduction = $enableProduction;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getEnableProduction()
    {
        return $this->enableProduction;
    }

    /**
     * @param array $configListeners
     * @return $this
     */
    public function setConfigListeners($configListeners)
    {
        $this->configListeners = $configListeners;
        return $this;
    }

    /**
     * @return array
     */
    public function getConfigListeners()
    {
        return $this->configListeners;
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
}