<?php

namespace SpiffyConfig\Annotation\Controller;

use Doctrine\Common\Annotations\Annotation;

abstract class AbstractType extends Annotation
{
    /**
     * @var string
     */
    public $name;
}