<?php

namespace Telefone\Service;

use \Telefone\Entity\TelefoneEntity as Entity;

class TelefoneService extends Entity {

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
