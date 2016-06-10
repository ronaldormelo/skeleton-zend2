<?php

namespace Contrato\Service;

use \Contrato\Entity\ContratoEntity as Entity;

class ContratoService extends Entity{
    
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
    
    /**
     * 
     */
    public function getPaginatorContrato($filter = NULL, $camposFilter = NULL) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('contrato')->columns([
                'id_contrato',
'dt_adesao',
'vl_saldo',
'id_situacao',
'id_usuario',
'id_contrato_origem',
'id_contrato_indicacao',

                ]);

        $where = [
        ];

        if (!empty($filter)) {

            foreach ($filter as $key => $value) {

                if ($value) {

                    if (isset($camposFilter[$key]['mascara'])) {

                        eval("\$value = " . $camposFilter[$key]['mascara'] . ";");
                    }

                    $where[$camposFilter[$key]['filter']] = '%' . $value . '%';
                }
            }
        }

        $select->where($where);
        //$select->order(['<campo> ASC']);

        return new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\DbSelect($select, $this->getAdapter()));
    }

}