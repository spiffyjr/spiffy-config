<?php

namespace SpiffyConfig\Annotation\Controller;

use SpiffyConfig\Annotation\Service;

/**
 * @Annotation
 */
class Invokable extends Service\Invokable
{
    /**
     * @var string
     */
    public $key = 'controllers';
}
