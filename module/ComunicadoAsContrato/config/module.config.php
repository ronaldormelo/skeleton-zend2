<?php

return array(
    'router' => array(
        'routes' => array(
            'comunicado_as_contrato-home' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/comunicado_as_contrato',
                    'defaults' => array(
                        'controller' => 'comunicado_as_contrato',
                        'action'     => 'index',
                    ),
                ),
            ),
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
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'comunicado_as_contrato' => 'ComunicadoAsContrato\Controller\ComunicadoAsContratoController',
            'comunicado_as_contrato-comunicadoascontrato' => 'Comunicado_as_contrato\Controller\ComunicadoascontratoController',

        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
