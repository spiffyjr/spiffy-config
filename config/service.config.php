<?php

return array(
    'factories' => array(
        'SpiffyConfig\Builder\Manager'  => 'SpiffyConfig\Builder\ManagerFactory',
        'SpiffyConfig\Config\Manager'   => 'SpiffyConfig\Config\ManagerFactory',
        'SpiffyConfig\Resolver\Manager' => 'SpiffyConfig\Resolver\ManagerFactory',
        'SpiffyConfig\ModuleOptions'    => 'SpiffyConfig\ModuleOptionsFactory',
    ),

    'invokables' => array(
        'SpiffyConfig\Handler\ConfigWriter' => 'SpiffyConfig\Handler\ConfigWriter',
        'SpiffyConfig\Handler\Runtime'      => 'SpiffyConfig\Handler\Runtime',
    )
);
