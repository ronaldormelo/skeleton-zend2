<?php

namespace TipoUsuario\Service;

use \TipoUsuario\Entity\TipoUsuarioEntity as Entity;

class TipoUsuarioService extends Entity {

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
