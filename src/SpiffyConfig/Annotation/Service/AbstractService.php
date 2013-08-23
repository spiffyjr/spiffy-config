<?php

namespace SpiffyConfig\Annotation\Service;

use Doctrine\Common\Annotations\Annotation;

abstract class AbstractService extends Annotation
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;
}