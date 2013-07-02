<?php

namespace SpiffyConfig\Builder;

use ArrayObject;
use ReflectionClass;
use SpiffyConfig\Resolver;
use Zend\Code\Reflection\FileReflection;

class Router extends AbstractRouter
{
    /**
     * {@inheritDoc}
     */
    public function build(Resolver\ResultInterface $result)
    {
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        $config = new ArrayObject();
        foreach ($result as $file) {
            $filename = $file->getRealPath();

            if (!in_array($file->getRealPath(), get_included_files())) {
                include $filename;
            }

            $fileRefl  = new FileReflection($filename);
            $classname = $fileRefl->getClass()->getName();

            $this->buildConfiguration($config, new ReflectionClass($classname), $classname);
        }

        $this->assembleRoutes($config);

        return $config->getArrayCopy();
    }
}
