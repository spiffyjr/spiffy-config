<?php

return array(
    'spiffy_config' => array(
        'enable_production' => false,

        'config_listeners' => array(
            'SpiffyConfig\RuntimeListener'
        )
    ),

    'service_manager' => include 'service.config.php'
);
