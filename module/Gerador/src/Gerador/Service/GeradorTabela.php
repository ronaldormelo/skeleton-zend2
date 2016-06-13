<?php

namespace Gerador\Service;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\TableIdentifier;

class GeradorTabela extends \Gerador\Entity\GeradorTabela {

    /**
     * 
     * @param type $where
     * @return type
     */
    public function select($where = null) {

        $select = new Select();
        $select->from(new TableIdentifier(
                $this->getTable()->table, 'INFORMATION_SCHEMA'
        ));
        $select->columns($this->getTable()->getColumns());
        $select->where($where);

        /** @var $lista GeradorTabela[] */
        /** @var $tratado GeradorTabela[] */
        $lista = $this->getTable()->select($select);
        $tratado = [];
        foreach ($lista as $item) {
            if ($item->getTableSchema() == $this->getTableSchema()) {
                $tratado[] = $item;
            }
        }

        return $tratado;
    }

    /**
     * 
     * @return type
     */
    public function getConfigDb() {

        if (!$this->config) {
            $gerador = require(BASE_PATCH . '/config/autoload/gerador.php');
            $this->setTableSchema($gerador['database']);
            $this->config = $gerador['db'];
        }

        return $this->config;
    }
    
    /**
     * 
     * @return type
     */
    public function setConfigDb() {

        self::$adapter = NULL;
        $this->config = \Estrutura\Service\ConfigService::getConfig('db');
    }

}
