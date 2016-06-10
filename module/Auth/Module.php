<?php

namespace Auth;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface {

    /**
     * 
     * @return type
     */
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * 
     * @return type
     */
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * 
     * @return type
     */
    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'Auth' => function($sm) {
                    $auth = new \Auth\View\Helper\Auth();
                    $auth->setServiceManager($sm);
                    return $auth;
                }
            ),
        );
    }

    /**
     * 
     * @return type
     */
    public function getServiceConfig() {
        return array(
            'factories' => [
                'Auth\Table\MyAuth' => function($sm) {

                    $sessionConfig = new \Zend\Session\Config\SessionConfig();
                    $sessionConfig->setOptions(array(
                        'cookie_lifetime' => 3600,
                        'gc_maxlifetime' => 3600,
                        'remember_me_seconds' => 3600,
                        'use_cookies' => true,
                    ));
                    $sessionManager = new \Zend\Session\SessionManager($sessionConfig);

                    return new \Auth\Table\MyAuth('auth', NULL, $sessionManager);
                },
                        'AuthService' => function($sm) {

                    $authService = new \Auth\Service\AuthService();
                    $authService->setServiceManager($sm);
                    return $authService->autenticar();
                }
                    ]
                );
            }

            /**
             * 
             * @param \Zend\Mvc\MvcEvent $e
             */
            public function onBootstrap(MvcEvent $e) {
                $eventManager = $e->getApplication()->getEventManager();
                $moduleRouteListener = new ModuleRouteListener();
                $moduleRouteListener->attach($eventManager);

                $locale = 'pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3';

                if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

                    $localeFromHttp = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

                    if (false === stripos($localeFromHttp, '-')) {

                        $locale = $localeFromHttp . '_' . strtoupper($localeFromHttp);
                    } else {

                        $locale = $localeFromHttp;
                    }
                }

                $translator = $e->getApplication()->getServiceManager()->get('translator');
                $translator->setLocale($locale)->setFallbackLocale('pt_BR');
            }

        }
        