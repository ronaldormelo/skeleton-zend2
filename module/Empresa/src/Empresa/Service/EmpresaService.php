<?php

namespace Empresa\Service;

use \Empresa\Entity\EmpresaEntity as Entity;

class EmpresaService extends Entity {

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
     * @param type $auth
     * @param type $local
     * @return type
     */
    public function listEmpresasUsuario($idUsuario) {


        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select('empresa')
                ->join(
                        'empresa_as_contrato', 'empresa_as_contrato.id_empresa = empresa.id_empresa'
                )
                ->join(
                        'contrato', 'contrato.id_contrato = empresa_as_contrato.id_contrato'
                )
                ->join(
                        ['contrato_pai' => 'contrato'], 'contrato_pai.id_contrato = empresa_as_contrato.id_contrato_pai', ['id_usuario_pai' => 'id_usuario'], \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'usuario', 'usuario.id_usuario = contrato_pai.id_usuario', ['nm_usuario_pai' => 'nm_usuario'], \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        ['empresa_as_contrato_pai' => 'empresa_as_contrato'], 'empresa_as_contrato_pai.id_contrato = contrato_pai.id_contrato AND empresa_as_contrato_pai.id_empresa = empresa_as_contrato.id_empresa', ['ur_link_empresa_pai' => 'ur_link_empresa'], \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->where([
            'contrato.id_usuario = ?' => $idUsuario,
            'empresa.id_situacao = ?' => $this->configList['situacao_ativo'],
        ]);
        $select->quantifier('DISTINCT');

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

    /**
     * 
     * @param type $auth
     * @param type $local
     * @return type
     */
    public function fetchAllAtivo() {

        $select = $this->getTable()->getTableGateway()->getSql()
                ->select()
                ->where(
                [
                    'empresa.id_situacao = ?' => $this->configList['situacao_ativo'],
                ]
        );

        return $this->getTable()->getTableGateway()->selectWith($select);
    }

    /**
     * 
     * @param type $auth
     * @param type $local
     * @return type
     */
    public function listEmpresasSuporte($idContrato) {


        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select('empresa')
                ->join(
                        'suporte_as_contrato', 'suporte_as_contrato.id_empresa = empresa.id_empresa'
                )
                ->where([
            'empresa.id_situacao = ?' => $this->configList['situacao_ativo'],
            'suporte_as_contrato.id_situacao = ?' => $this->configList['situacao_ativo'],
            'suporte_as_contrato.id_contrato = ?' => $idContrato,
        ]);

        $select->quantifier('DISTINCT');

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

}
