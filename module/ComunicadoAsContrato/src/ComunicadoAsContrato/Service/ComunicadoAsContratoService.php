<?php

namespace ComunicadoAsContrato\Service;

use \ComunicadoAsContrato\Entity\ComunicadoAsContratoEntity as Entity;

class ComunicadoAsContratoService extends Entity {

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
