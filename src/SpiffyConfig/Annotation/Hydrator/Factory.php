<?php

namespace SpiffyConfig\Annotation\Hydrator;

use SpiffyConfig\Annotation\Service;

/**
 * @Annotation
 */
class Factory extends Service\Factory
{
    /**
     * @var string
     */
    public $key = 'hydrators';
}
