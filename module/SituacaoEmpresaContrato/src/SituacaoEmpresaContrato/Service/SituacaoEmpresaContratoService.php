<?php

namespace SituacaoEmpresaContrato\Service;

use \SituacaoEmpresaContrato\Entity\SituacaoEmpresaContratoEntity as Entity;

class SituacaoEmpresaContratoService extends Entity {

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
