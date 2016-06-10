<?php

namespace TipoComunicado\Service;

use \TipoComunicado\Entity\TipoComunicadoEntity as Entity;

class TipoComunicadoService extends Entity{
    
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
    public function getPaginatorTipoComunicado($filter = NULL, $camposFilter = NULL) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('tipo_comunicado')->columns([
                'id_tipo_comunicado',
'nm_tipo_comunicado',

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