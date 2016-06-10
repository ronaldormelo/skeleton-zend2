<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'application-index',
                        'action' => 'index',
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
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory', // <- Adicione 
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
            'application-index' => 'Application\Controller\IndexController',
            'application-parametrizacao' => 'Application\Controller\ParametrizacaoController',
            'application-event' => 'Application\Controller\EventController',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
                'my-first-route' => array(
                    'options' => array(
                        'route' => 'excluir usuarios congelados',
                        'defaults' => array(
                            'controller' => 'mcnetwork-console',
                            'action' => 'excluir-usuarios-congelados'
                        ),
                    ),
                ),
                'my-second-route' => array(
                    'options' => array(
                        'route' => 'zerar saldo usuarios',
                        'defaults' => array(
                            'controller' => 'mcnetwork-console',
                            'action' => 'zerar-saldo-usuarios'
                        ),
                    ),
                ),
            ),
        ),
    ),
//    'navigation' => array(
//        'default' => array(
//            array(
//                'label' => 'Home',
//                'route' => 'home',
//            ),
//        ),
//    ),
);
