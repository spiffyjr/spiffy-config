<?php

namespace SpiffyConfig\Collector;

use SpiffyConfig\ModuleOptions;
use Zend\Mvc\MvcEvent;
use ZendDeveloperTools\Collector\CollectorInterface;

class RebuildCollector implements CollectorInterface
{
    const NAME     = 'spiffy_config_rebuild';
    const PRIORITY = 100;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @param ModuleOptions $options
     */
    public function __construct(ModuleOptions $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getPriority()
    {
        return self::PRIORITY;
    }

    /**
     * {@inheritDoc}
     */
    public function collect(MvcEvent $mvcEvent)
    {

    }

    /**
     * @return \SpiffyConfig\ModuleOptions
     */
    public function getOptions()
    {
        return $this->options;
    }
}
