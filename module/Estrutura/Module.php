<?php

namespace Estrutura;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

    /**
     * 
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e) {

        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {

            $controller = $e->getTarget();
            $controllerClass = get_class($controller);

            //Seta layout
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

            $config = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$moduleNamespace])) {

                if (is_array($config['module_layouts'][$moduleNamespace])) {

                    $controller->layout($config['module_layouts'][$moduleNamespace][$config['tema']]);
                } else {

                    $controller->layout($config['module_layouts'][$moduleNamespace]);
                }
            } else {

                $controller->layout('layout/' . $config['tema'] . '/layout');
            }
        }
                , 100);
    }

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
            'invokables' => array(
                'formataCPFouCNPJ' => '\Estrutura\View\Helper\FormataCPFouCNPJ',
                'Config' => '\Estrutura\View\Helper\ConfigService'
            ),
        );
    }

    /**
     * 
     * @return type
     */
    public function getServiceConfig() {
        return array(
            'factories' => array(
            ),
        );
    }

}
