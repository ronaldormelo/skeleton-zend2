<?php

namespace Estado\Service;

use \Estado\Entity\EstadoEntity as Entity;

class EstadoService extends Entity {

    /**
     *
     * @var type 
     */
    protected $configList;

    /**
     * @param type $configList
     */
    public function setConfigList($configList) {
        $this->configList = $configList;
    }

}
