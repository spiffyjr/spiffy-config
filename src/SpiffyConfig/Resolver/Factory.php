<?php

namespace SpiffyConfig\Resolver;

class Factory
{
    /**
     * @var array
     */
    protected $map = array(
        'file' => 'SpiffyConfig\Resolver\File'
    );

    /**
     * @param array $spec
     * @return ResolverInterface
     * @throws \InvalidArgumentException
     */
    public function create(array $spec)
    {
        $type    = isset($spec['type']) ? $spec['type'] : null;
        $options = isset($spec['options']) ? $spec['options'] : array();

        if (!$type) {
            throw new \InvalidArgumentException('missing type for resolver');
        }

        if (isset($this->map[$type])) {
            $type = $this->map[$type];
        }

        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf(
                'invalid resolver type "%s"',
                $type
            ));
        }

        $resolver = new $type();

        if (!$resolver instanceof ResolverInterface) {
            throw new \InvalidArgumentException('resolver must implement Resolver\ResolverInterface');
        }

        $resolver->setOptions($options);
        return $resolver;
    }
}