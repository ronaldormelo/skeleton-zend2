<?php

namespace Login\Service;

use \Login\Entity\LoginEntity as Entity;

class LoginService extends Entity {

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

    public function listLoginUsuario($idUsuario) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('login')
                        ->where([
                            'login.id_usuario = ?' => $idUsuario,
                        ])->limit(1);

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

}
