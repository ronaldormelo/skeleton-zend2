<?php

namespace Config;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

    /**
     * 
     */
    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * 
     */
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * 
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
            'invokables' => array(
                'ConfigService' => '\Config\View\Helper\ConfigService'
            ),
        );
    }

    /**
     * 
     * @return array
     */
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Config\Service\ConfigService' => function($sm) {

                    return new \Config\Service\ConfigService();
                },
            )
        );
    }

}
