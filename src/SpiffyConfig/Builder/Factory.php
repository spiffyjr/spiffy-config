<?php

namespace SpiffyConfig\Builder;

class Factory
{
    /**
     * @var array
     */
    protected $map = array(
        'route'    => 'SpiffyConfig\Builder\RouteBuilder',
        'service'  => 'SpiffyConfig\Builder\ServiceBuilder',
        'template' => 'SpiffyConfig\Builder\TemplateBuilder',
    );

    /**
     * @param array $spec
     * @return BuilderInterface
     * @throws \InvalidArgumentException
     */
    public function create(array $spec)
    {
        $type    = isset($spec['type']) ? $spec['type'] : null;
        $options = isset($spec['options']) ? $spec['options'] : array();

        if (!$type) {
            throw new \InvalidArgumentException('missing type for builder');
        }

        if (isset($this->map[$type])) {
            $type = $this->map[$type];
        }

        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf(
                'invalid builder type "%s"',
                $type
            ));
        }

        $builder = new $type();

        if (!$builder instanceof BuilderInterface) {
            throw new \InvalidArgumentException('builder must implement Builder\BuilderInterface');
        }

        $builder->setOptions($options);
        return $builder;
    }
}