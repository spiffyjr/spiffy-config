<?php

namespace SpiffyConfig\Resolver;

use Symfony\Component\Finder\Finder;

class File extends AbstractResolver
{
    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var FileOptions
     */
    protected $options;

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
        return $this->getFileFinder();
    }
}
