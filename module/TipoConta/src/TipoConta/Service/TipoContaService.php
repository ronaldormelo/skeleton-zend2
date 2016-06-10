<?php

namespace TipoConta\Service;

use \TipoConta\Entity\TipoContaEntity as Entity;

class TipoContaService extends Entity {

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
