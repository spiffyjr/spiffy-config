<?php

namespace SpiffyConfig\Builder;

use ArrayObject;
use ReflectionClass;
use SpiffyConfig\Resolver;
use SplFileInfo;
use Zend\Code\Reflection\FileReflection;

abstract class AbstractServiceManager extends AbstractAnnotationBuilder
{
    /**
     * @var array
     */
    protected $defaultListeners = array(
        'SpiffyConfig\\Builder\\ServiceListener',
    );

    /**
     * {@inheritDoc}
     */
    public function build(Resolver\ResultInterface $result)
    {
        $config = new ArrayObject();

        foreach ($result as $entry) {
            $this->buildConfiguration($config, $entry);
        }

        return $config->getArrayCopy();
    }

    /**
     * @param ArrayObject $config
     * @param $file
     * @throws \RuntimeException
     */
    protected function buildConfiguration(ArrayObject $config, $file)
    {
        if (!$file instanceof SplFileInfo) {
            throw new \RuntimeException(
                'Builder only operates on Resolver\File results'
            );
        }

        $filename = $file->getRealPath();

        if (!in_array($file->getRealPath(), get_included_files())) {
            include $filename;
        }

        try {
            $fileRefl  = new FileReflection($filename);
            $className = $fileRefl->getClass()->getName();
        } catch (\Exception $e) {
            return;
        }

        $reader      = $this->getAnnotationReader();
        $annotations = $reader->getClassAnnotations(new ReflectionClass($className));

        if ($this->checkForExclude($annotations)) {
            return;
        }

        $name = $this->discoverName($annotations);
        $name = $name ? $name : $className;

        $spec = new ArrayObject();
        $this->configure($annotations, $spec, $className, $name);

        $config->exchangeArray(array_merge($config->getArrayCopy(), $spec->getArrayCopy()));
    }

    /**
     * @param $annotations
     * @return mixed|null
     */
    protected function discoverName($annotations)
    {
        $params   = array('annotations' => $annotations);
        $callback = function ($r) {
            return (true === $r);
        };
        $results = $this->getEventManager()->trigger(__FUNCTION__, $this, $params, $callback);

        return is_string($results->last()) ? $results->last() : null;
    }

    /**
     * @param $annotations
     * @param ArrayObject $spec
     * @param string $className
     * @param string $name
     */
    protected function configure($annotations, ArrayObject $spec, $className, $name)
    {
        if ($this->checkForExclude($annotations)) {
            return;
        }

        $eventManager = $this->getEventManager();
        $eventManager->trigger(
            __FUNCTION__,
            $this,
            array(
                'annotations' => $annotations,
                'spec'        => $spec,
                'className'   => $className,
                'name'        => $name
            )
        );
    }

    /**
     * @param $annotations
     * @return bool
     */
    protected function checkForExclude($annotations)
    {
        $params   = array('annotations' => $annotations);
        $callback = function ($r) {
            return (true === $r);
        };
        $results = $this->getEventManager()->trigger(__FUNCTION__, $this, $params, $callback);
        return (bool) $results->last();
    }
}
