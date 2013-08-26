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

    'spiffy_config' => array(
        'collections' => array(
            'default' => array()
        ),

        'resolvers' => array(),
    ),

    'service_manager' => array(
        'factories' => array(
            'SpiffyConfig\ModuleOptions' => 'SpiffyConfig\ModuleOptionsFactory'
        )
    )
);
