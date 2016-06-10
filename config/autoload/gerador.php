<?php

return [
    'db' => array(
        'driver' => 'Pdo',
        'username' => 'root',
        'password' => '',
        'dsn' => 'mysql:dbname=information_schema;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'database' => 'skeleton',
    'location' => BASE_PATCH . '/module/',
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    )
];
