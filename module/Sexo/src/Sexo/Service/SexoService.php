<?php

namespace Sexo\Service;

use \Sexo\Entity\SexoEntity as Entity;

class SexoService extends Entity {

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
