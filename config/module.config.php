<?php

return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'spiffy_config_build' => array(
                    'options' => array(
                        'route'    => 'spiffyconfig build',
                        'defaults' => array(
                            'controller' => 'SpiffyConfig\Controller\Cli',
                            'action'     => 'build'
                        )
                    )
                ),
                'spiffy_config_clear' => array(
                    'options' => array(
                        'route'    => 'spiffyconfig clear',
                        'defaults' => array(
                            'controller' => 'SpiffyConfig\Controller\Cli',
                            'action'     => 'clear'
                        )
                    )
                )
            )
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'SpiffyConfig\Controller\Cli' => 'SpiffyConfig\Controller\Cli'
        )
    ),

    'service_manager' => array(
        'factories' => array(
            'SpiffyConfig\Collector\RebuildCollector' => 'SpiffyConfig\Collector\RebuildCollectorFactory',
            'SpiffyConfig\ModuleOptions'              => 'SpiffyConfig\ModuleOptionsFactory'
        )
    ),

    'view_manager' => array(
        'template_map' => array(
            'zend-developer-tools/toolbar/rebuild' => __DIR__ . '/../view/zend-developer-tools/toolbar/rebuild.phtml'
        )
    ),

    'zenddevelopertools' => array(
        'collectors' => array(
            'spiffy_config_rebuild' => 'SpiffyConfig\Collector\RebuildCollector',
        ),

        'toolbar' => array(
            'entries' => array(
                'spiffy_config_rebuild' => 'zend-developer-tools/toolbar/rebuild',
            ),
        ),
    )
);
