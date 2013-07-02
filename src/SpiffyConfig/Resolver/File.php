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
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = new FileOptions($options);
    }

    /**
     * @return Finder
     */
    public function getFileFinder()
    {
        if (!$this->finder) {
            $this->finder = new Finder();

            if ($this->options->getName()) {
                $this->finder->name($this->options->getName());
            }

            foreach ($this->options->getPaths() as $path) {
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
        if (0 === count($this->options->getPaths())) {
            return new Result(new ArrayIterator(array()));
        }

        return new Result($this->getFileFinder()->getIterator());
    }
}
