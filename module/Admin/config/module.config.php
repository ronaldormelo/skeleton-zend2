<?php

namespace Admin;

return array(
    'router' => array(
        'routes' => array(
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
                'text_domain' => __NAMESPACE__, // Sem isso, o textDomain, usado pelo Zend\I18n\Translator\Translator fica 'default' e como o 'default' já foi definido quando foi adicionado no Application/config/module.config.php há um conflito e fica prevalecendo o do modulo Application
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'admin-index' => 'Admin\Controller\IndexController',
            'admin-relatorio' => 'Admin\Controller\RelatorioController',
        ),
    ),        
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        )
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
