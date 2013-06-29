<?php

namespace SpiffyConfig\Builder;

use Zend\Code\Reflection\FileReflection;

class ControllerLoader extends AbstractAnnotationBuilder
{
    public function build()
    {
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        $finder = $this->resolver->resolve();
        $config = array();

        foreach ($finder as $file) {
            $filename = $file->getRealPath();

            if (!in_array($file->getRealPath(), get_included_files())) {
                include $filename;
            }

            $refl       = new FileReflection($filename);
            $className  = $refl->getClass()->getName();
            $implements = class_implements($className);

            if (!isset($implements['Zend\Stdlib\DispatchableInterface'])) {
                continue;
            }

            $config['controllers']['invokables'][$className] = $className;
        }

        return $config;
    }
}
