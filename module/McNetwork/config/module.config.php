<?php

namespace McNetwork;

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
            'mcnetwork-cursos' => 'McNetwork\Controller\CursosController',
            'mcnetwork-index' => 'McNetwork\Controller\IndexController',
            'mcnetwork-rede' => 'McNetwork\Controller\RedeController',
            'mcnetwork-relatorio' => 'McNetwork\Controller\RelatorioController',
            'mcnetwork-console' => 'McNetwork\Controller\ConsoleController',
            'mcnetwork-divisao' => 'McNetwork\Controller\DivisaoController',
            'mcnetwork-suporte' => 'McNetwork\Controller\SuporteController',
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
                'my-first-route' => array(
                    'options' => array(
                        'route'    => 'excluir usuarios congelados',
                        'defaults' => array(
                            'controller' => 'mcnetwork-console',
                            'action'     => 'excluir-usuarios-congelados'
                        ),
                    ),
                ),
                'my-second-route' => array(
                    'options' => array(
                        'route'    => 'zerar saldo usuarios',
                        'defaults' => array(
                            'controller' => 'mcnetwork-console',
                            'action'     => 'zerar-saldo-usuarios'
                        ),
                    ),
                ),
            ),
        ),
    ),
);
