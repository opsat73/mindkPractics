<?php

return array(
    'mode'        => 'dev',
    'routes'      => include('routes.php'),
    'main_layout' => __DIR__.'/../../src/Blog/views/layout.html.php',
    'error_500'   => __DIR__.'/../../src/Blog/views/500.html.php',
    'pdo'         => array(
        'dns'      => 'mysql:dbname=mindk;host=127.0.0.1',
        'user'     => 'root',
        'password' => 'RE3r9D+z'
    ),
    'security'    => array(
        'user_class'  => 'Blog\\Model\\User',
        'login_route' => 'login'
    ),
    'localization' => array(
        'default' => 'EN',
        'available' => array('RU' => 'ru_RU.utf8',
                             'EN' => 'en_US.utf8')
    )
);