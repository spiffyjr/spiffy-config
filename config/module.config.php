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
        'enable_production' => true,

        'autoload_file' => 'config/autoload/spiffyconfig.local.php',

        'resolvers' => array(
            'controllers' => array(
                'type' => 'SpiffyConfig\Resolver\File',
                'options' => array(
                    'paths' => array(
                        'module/Application/src/Application/Controller'
                    ),
                    'name' => '*.php',
                ),
                'builders' => array(
                    'SpiffyConfig\Builder\ControllerLoader',
                    'SpiffyConfig\Builder\Router',
                )
            ),

            'template_map' => array(
                'type' => 'SpiffyConfig\Resolver\File',
                'options' => array(
                    'paths' => array(
                        'module/Application/view'
                    ),
                    'name' => '*.phtml'
                ),
                'builders' => array(
                    'SpiffyConfig\Builder\TemplateMap'
                )
            )
        ),

        'config_listeners' => array(
            'SpiffyConfig\RuntimeListener'
        )
    ),

    'service_manager' => include 'service.config.php'
);
