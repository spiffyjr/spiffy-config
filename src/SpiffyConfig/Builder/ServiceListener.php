<?php

namespace SpiffyConfig\Builder;

use SpiffyConfig\Annotation\Service;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

class ServiceListener extends AbstractListenerAggregate
{
    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('configure', array($this, 'onConfigure'));
        $this->listeners[] = $events->attach('discoverName', array($this, 'onDiscoverName'));
    }

    /**
     * @param EventInterface $e
     * @throws \RuntimeException
     * @return string
     */
    public function onConfigure(EventInterface $e)
    {
        $annotations = $e->getParam('annotations');
        $name        = $e->getParam('name');
        $className   = $e->getParam('className');
        $spec        = $e->getParam('spec');

        foreach ($annotations as $annotation) {
            if (!$annotation instanceof Service\AbstractService) {
                continue;
            }

            if (!$annotation->key) {
                throw new \RuntimeException(sprintf(
                    'Missing key for "%s" found in "%s"',
                    get_class($annotation),
                    $className
                ));
            }

            if (!$annotation->type) {
                throw new \RuntimeException(sprintf(
                    'Missing key for "%s" found in "%s"',
                    get_class($annotation),
                    $className
                ));
            }

            $keyParts = explode('|', $annotation->key);

            foreach ($keyParts as $part) {
                $spec = &$spec[$part];
            }

            $spec[$annotation->type][$name] = $className;
        }
    }

    /**
     * @param EventInterface $e
     * @return null|string
     */
    public function onDiscoverName(EventInterface $e)
    {
        $annotations = $e->getParam('annotations');

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Service\AbstractService) {
                return $annotation->name;
            }
        }
        return null;
    }
}