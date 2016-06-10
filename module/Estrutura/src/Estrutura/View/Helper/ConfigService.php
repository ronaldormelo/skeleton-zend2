<?php

namespace Estrutura\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ConfigService extends AbstractHelper {

    public function __invoke($indice) {

        return \Estrutura\Service\ConfigService::getConfig($indice);
    }

}
