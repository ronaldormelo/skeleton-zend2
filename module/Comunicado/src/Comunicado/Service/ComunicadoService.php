<?php

namespace Comunicado\Service;

use \Comunicado\Entity\ComunicadoEntity as Entity;

class ComunicadoService extends Entity {

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

    /**
     * 
     * @return type
     */
    public function fetchAllAtivos() {
        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select('comunicado')
                        ->where([
                            'comunicado.id_situacao = ?' => $this->configList['situacao_ativo'],
                        ])->order('comunicado.id_comunicado DESC')->limit(10);

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

}
