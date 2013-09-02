<?php

namespace SpiffyConfig\Annotation\RouteManager;

use SpiffyConfig\Annotation\Service;

/**
 * @Annotation
 */
class Invokable extends Service\Invokable
{
    /**
     * @var string
     */
    public $key = 'route_manager';
}
