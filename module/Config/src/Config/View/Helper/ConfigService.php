<?php

namespace Config\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ConfigService extends AbstractHelper {

    public function __invoke($indice) {

        return \Config\Service\ConfigService::getConfig($indice);
    }

}
