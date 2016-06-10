<?php

namespace Usuario\Service;

use \Usuario\Entity\UsuarioEntity as Entity;

class UsuarioService extends Entity{
    
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
    public function getPaginatorUsuario($filter = NULL, $camposFilter = NULL) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('usuario')->columns([
                'id_usuario',
'nm_usuario',
'dt_nascimento',
'nu_rg',
'nu_cpf',
'nm_profissao',
'nm_nacionalidade',
'id_sexo',
'id_estado_civil',
'id_tipo_usuario',
'id_situacao_usuario',
'id_email',
'id_telefone',
'id_endereco',

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