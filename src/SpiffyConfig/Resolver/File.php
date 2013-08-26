<?php

namespace SpiffyConfig\Resolver;

use ArrayIterator;
use Symfony\Component\Finder\Finder;

class File extends AbstractResolver
{
    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var array
     */
    protected $result;

    /**
     * @return $this
     */
    public function reset()
    {
        $this->finder = null;
        $this->result = null;
        return $this;
    }

    /**
     * @return Finder
     */
    public function getFinder()
    {
        if (!$this->finder instanceof Finder) {
            $this->finder = new Finder();

            if (isset($this->options['name'])) {
                $this->finder->name($this->options['name']);
            }

            $paths = isset($this->options['paths']) ? $this->options['paths'] : array();
            foreach ($paths as $path) {
                $this->finder->in($path);
            }
        }
        return $this->finder;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve()
    {
        if (!$this->result) {
            $paths = isset($this->options['paths']) ? $this->options['paths'] : array();
            if (0 === count($paths)) {
                $this->result = array();
            } else {
                $this->result = $this->getFinder()->getIterator();
            }
        }
        return $this->result;
    }
}
