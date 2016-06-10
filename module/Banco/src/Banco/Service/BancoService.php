<?php

namespace Banco\Service;

use \Banco\Entity\BancoEntity as Entity;

class BancoService extends Entity {

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
