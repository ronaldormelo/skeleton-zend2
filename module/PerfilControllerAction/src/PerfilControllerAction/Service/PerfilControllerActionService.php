<?php

namespace PerfilControllerAction\Service;

use \PerfilControllerAction\Entity\PerfilControllerActionEntity as Entity;

class PerfilControllerActionService extends Entity {

    /**
     *
     * @var type 
     */
    protected $configList;

    /**
     * 
     * @param type $configList
     */
    public function setConfigList($configList) {
        $this->configList = $configList;
    }

}
