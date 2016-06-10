<?php

namespace SituacaoUsuario\Service;

use \SituacaoUsuario\Entity\SituacaoUsuarioEntity as Entity;

class SituacaoUsuarioService extends Entity {

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
