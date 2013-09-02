<?php

namespace SpiffyConfig\Annotation\Service;

use Doctrine\Common\Annotations\Annotation;

abstract class AbstractService extends Annotation
{
    /**
     * @var string
     */
    public $key = 'service_manager';

    /**
     * @var null|bool
     */
    public $shared = null;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;
}