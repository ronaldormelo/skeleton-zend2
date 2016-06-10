<?php

namespace EstadoCivil\Service;

use \EstadoCivil\Entity\EstadoCivilEntity as Entity;

class EstadoCivilService extends Entity {

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
