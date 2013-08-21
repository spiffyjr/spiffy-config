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
        'collection_manager' => array(
            'abstract_factories' => array(
                'SpiffyConfig\Config\AbstractCollectionFactory',
            ),
        ),

        'resolver_manager' => array(
            'abstract_factories' => array(
                'SpiffyConfig\Resolver\AbstractFactory'
            ),
        ),

        'collections' => array(
            'default' => array()
        ),

        'resolvers' => array(
            'controller' => array(
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
            ),
        ),

        'handlers' => array(
            'SpiffyConfig\Handler\Runtime'
        ),
    ),

    'service_manager' => include 'service.config.php'
);
