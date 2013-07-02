<?php

namespace SpiffyConfig\Config;

class Collection
{
    /**
     * @var array
     */
    protected $configs = array();

    /**
     * @param Config $config
     * @return $this
     */
    public function addConfig(Config $config)
    {
        $this->configs[] = $config;
        return $this;
    }

    /**
     * @param array $configs
     * @return $this
     */
    public function setConfigs($configs)
    {
        foreach ($configs as $config) {
            $this->addConfig($config);
        }
        return $this;
    }

    /**
     * @return Config[]
     */
    public function getConfigs()
    {
        return $this->configs;
    }
}
