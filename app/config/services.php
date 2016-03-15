<?php

return array(
    'request'      => array(
        'class' => 'Framework\Request\Request'
    ),
    'response'     => array(
        'class' => 'Framework\Response\Response'
    ),
    'session'      => array(
        'class' => 'Framework\Session\SessionManager'
    ),
    'security'     => array(
        'class' => 'Framework\Security\Security'
    ),
    'router'       => array(
        'class'       => 'Framework\Router\Router',
        'config_file' => __DIR__.'/routes.php'
    ),
    'db'           => array(
        'class'           => '\PDO',
        'init_parameters' => array(
            "mysql:host=172.17.0.1;dbname=mindk;",
            "root",
            "root"
        )
    ),
    'localization' => array(
        'class'       => 'Framework\Localization\LocalizationManager',
        'config_file' => __DIR__.'/localization.php'
    )
);