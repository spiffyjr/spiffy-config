<?php

namespace SpiffyConfig\Annotation\RouteManager;

use SpiffyConfig\Annotation\Service;

/**
 * @Annotation
 */
class Factory extends Service\Factory
{
    /**
     * @var string
     */
    public $key = 'route_manager';
}
