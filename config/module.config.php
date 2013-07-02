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
            'factory_config' => array(
                'default' => array(
                    /*
                    array(
                        'resolver' => 'controllers',
                        'builders' => array(
                            'ControllerLoader',
                            'Router',
                        )
                    ),

                    array(
                        'resolver' => 'phtml',
                        'builders' => array(
                            'TemplateMap'
                        )
                    )
                    */
                )
            )
        ),

        'handlers' => array(
            'SpiffyConfig\Handler\Runtime'
        ),

        'resolvers' => array(
            'factory_config' => array(
                'controllers' => array(
                    'type' => 'SpiffyConfig\Resolver\File',
                    'options' => array(
                        'paths' => array(
                            //'module/Application/src/Application/Controller'
                        ),
                        'name' => '*.php',
                    ),
                ),

                'phtml' => array(
                    'type' => 'SpiffyConfig\Resolver\File',
                    'options' => array(
                        'paths' => array(
                            //'module/Application/view'
                        ),
                        'name' => '*.phtml'
                    ),
                )
            )
        ),
    ),

    'service_manager' => include 'service.config.php'
);
