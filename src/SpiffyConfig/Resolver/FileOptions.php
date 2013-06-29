<?php

namespace SpiffyConfig\Resolver;

use Zend\Stdlib\AbstractOptions;

class FileOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $paths = array();

    /**
     * @var string
     */
    protected $name;

    /**
     * @param array $paths
     * @return FileOptions
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;
        return $this;
    }

    /**
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * @param string $name
     * @return FileOptions
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}