<?php

namespace Perfil\Service;

use \Perfil\Entity\PerfilEntity as Entity;

class PerfilService extends Entity {

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
