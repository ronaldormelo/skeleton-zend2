<?php

namespace TipoTelefone\Service;

use \TipoTelefone\Entity\TipoTelefoneEntity as Entity;

class TipoTelefoneService extends Entity {

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
