<?php

return array(
    'factories' => array(
        'SpiffyConfig\Builder\Manager'  => 'SpiffyConfig\Builder\ManagerFactory',
        'SpiffyConfig\Config\Manager'   => 'SpiffyConfig\Config\ManagerFactory',
        'SpiffyConfig\Resolver\Manager' => 'SpiffyConfig\Resolver\ManagerFactory',
        'SpiffyConfig\ModuleOptions'    => 'SpiffyConfig\ModuleOptionsFactory',
    ),

    'invokables' => array(
        'SpiffyConfig\Handler\Autoload' => 'SpiffyConfig\Handler\Autoload',
        'SpiffyConfig\Handler\Runtime'  => 'SpiffyConfig\Handler\Runtime',
    )
);
