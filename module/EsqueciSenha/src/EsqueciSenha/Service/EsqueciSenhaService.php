<?php

namespace EsqueciSenha\Service;

use \EsqueciSenha\Entity\EsqueciSenhaEntity as Entity;

class EsqueciSenhaService extends Entity {

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
