<?php

namespace Cidade\Service;

use \Cidade\Entity\CidadeEntity as Entity;

class CidadeService extends Entity {

    /**
     *
     * @var type 
     */
    protected $configList;

    /**
     * @param type $configList
     */
    public function setConfigList($configList) {
        $this->configList = $configList;
    }

    public function fetchAllEstado($params) {

        $resultSet = NULL;

        if (isset($params['id_estado']) && $params['id_estado']) {

            $resultSet = $this->select(
                    [
                        'cidade.id_estado = ? ' => $params['id_estado']
                    ]
            );
        }
        return $resultSet;
    }

}
