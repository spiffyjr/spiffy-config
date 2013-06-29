<?php

namespace SpiffyConfig\Annotation\Route;

/**
 * @Annotation
 */
class Segment extends AbstractType
{
    /**
     * @var string
     */
    public $type = 'segment';

    /**
     * @var array
     */
    public $constraints = array();
}
