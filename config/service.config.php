<?php

return array(
    'factories' => array(
        'SpiffyConfig\ConfigManager' => 'SpiffyConfig\ConfigManagerFactory',
        'SpiffyConfig\ModuleOptions' => 'SpiffyConfig\ModuleOptionsFactory',
    ),

    'invokables' => array(
        'SpiffyConfig\CacheListener'   => 'SpiffyConfig\CacheListener',
        'SpiffyConfig\RuntimeListener' => 'SpiffyConfig\RuntimeListener'
    )
);
