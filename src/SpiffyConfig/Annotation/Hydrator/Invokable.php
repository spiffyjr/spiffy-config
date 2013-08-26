<?php

namespace SpiffyConfig\Annotation\Hydrator;

use SpiffyConfig\Annotation\Service;

/**
 * @Annotation
 */
class Invokable extends Service\Invokable
{
    /**
     * @var string
     */
    public $key = 'hydrators';
}
