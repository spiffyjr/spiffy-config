<?php

namespace SpiffyConfig\Builder;

use SpiffyConfig\Resolver;
use Zend\Code\Reflection\FileReflection;

class ControllerLoader implements BuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(Resolver\ResultInterface $result)
    {
        $config = array();

        foreach ($result as $file) {
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
