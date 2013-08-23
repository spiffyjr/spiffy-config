<?php

namespace SpiffyConfig\Annotation\Controller;

use SpiffyConfig\Annotation\Service;

/**
 * @Annotation
 */
class Factory extends Service\Invokable
{
    /**
     * @var string
     */
    public $key = 'controllers';
}
