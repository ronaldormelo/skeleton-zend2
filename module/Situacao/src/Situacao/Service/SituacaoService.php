<?php

namespace Situacao\Service;

use \Situacao\Entity\SituacaoEntity as Entity;

class SituacaoService extends Entity {

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
