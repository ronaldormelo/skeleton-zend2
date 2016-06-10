<?php

return array(
    'db' => array(
        'username' => 'root',
        'password' => '',
        'dsn' => 'mysql:dbname=skeleton;host=localhost',
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'dominio' => 'http://skeleton.dev',
    'smtp_options' => array(
        'no-reply' => array(
            'name' => 'no-reply',
            'host' => 'localhost',
            'port' => 25,
            'connection_class' => 'plain',
            'connection_config' => array (
//				'ssl' => 'tls',
                'username' => 'no-reply@mcnetwork.com.br',
                'password' => 'Fl@m&ng0'
            ),
        ),
        'contato' => array(
            'name' => 'contato',
            'host' => 'localhost',
            'port' => 25,
            'connection_class' => 'plain',
            'connection_config' => array(
//				'ssl' => 'tls',
                'username' => 'contato@mcnetwork.com.br',
                'password' => 'P@lm&1r@s'
            ),
        ),
    )
);
