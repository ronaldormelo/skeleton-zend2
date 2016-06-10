<?php

namespace Estrutura\Service;

class ConfigService {

    private static $config;

    public static function getConfig($indice) {
        
        if (!isset(self::$config[$indice])) {
            
            $config = self::getDados($indice);
            self::$config[$indice] = $config;
        }

        return self::$config[$indice];
    }

    private static function getDados($indice) {
        
        $globals = require(BASE_PATCH . '/config/autoload/global.php');
        $ambiente = require(BASE_PATCH . '/config/autoload/' . APPLICATION_ENV . '.php');

        if (is_array($globals) && is_array($ambiente)) {
            if (isset($globals[$indice]) && isset($ambiente[$indice])) {

                return array_merge($globals[$indice], $ambiente[$indice]);
            }
            if (isset($ambiente[$indice])) {

                return $ambiente[$indice];
            }
            if (isset($globals[$indice])) {

                return $globals[$indice];
            }
        }
    }
}
