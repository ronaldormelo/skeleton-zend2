<?php

namespace McNetwork\Service;

Use McNetwork\Entity\RedeEntity as Entity;
use Zend\Session\Container;

class RedeService extends Entity {

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
     * @param type $auth
     * @param type $nivel
     * @return type
     */
    public function listRede($auth, $nivel) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('usuario')
                        ->join(
                                'contrato', 'contrato.id_usuario = usuario.id_usuario'
                        )
                        ->join(
                                'contrato_as_contrato', 'contrato_as_contrato.id_contrato_filho = contrato.id_contrato'
                        )
                        ->join(
                                'situacao_usuario', 'situacao_usuario.id_situacao_usuario = usuario.id_situacao_usuario'
                        )
                        ->join(
                                'email', 'email.id_email = usuario.id_email'
                        )
                        ->join(
                                'telefone', 'telefone.id_telefone = usuario.id_telefone'
                        )
                        ->where([
                            'contrato_as_contrato.id_nivel = ?' => $nivel,
                            'contrato_as_contrato.id_contrato' => $auth->id_contrato
                        ])->order('id_contratos_as_contratos');

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

    /**
     * 
     * @param type $auth
     * @param type $nivel
     * @return type
     */
    public function listPendentes($auth) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('contrato')
                        ->join(
                                'usuario', 'usuario.id_usuario = contrato.id_usuario'
                        )
                        ->join(
                                'situacao_usuario', 'situacao_usuario.id_situacao_usuario = usuario.id_situacao_usuario'
                        )
                        ->join(
                                'email', 'email.id_email = usuario.id_email'
                        )
                        ->join(
                                'telefone', 'telefone.id_telefone = usuario.id_telefone'
                        )
                        ->join(
                                'contrato_as_contrato', 'contrato.id_contrato = contrato_as_contrato.id_contrato_filho', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                        )
                        ->where([
                            'contrato_as_contrato.id_contrato_filho IS NULL',
                            'contrato.id_contrato_origem' => $auth->id_contrato
                        ])->order('contrato.id_contrato');

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

    /**
     * 
     * @param type $auth
     * @param type $nivel
     * @return type
     */
    public function listIndicacao($auth) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select('contrato')
                        ->join(
                                'usuario', 'usuario.id_usuario = contrato.id_usuario'
                        )
                        ->join(
                                'situacao_usuario', 'situacao_usuario.id_situacao_usuario = usuario.id_situacao_usuario'
                        )
                        ->join(
                                'email', 'email.id_email = usuario.id_email'
                        )
                        ->join(
                                'telefone', 'telefone.id_telefone = usuario.id_telefone'
                        )
                        ->where([
                            'usuario.id_situacao_usuario = ?' => $this->configList['situacao_usuario_ativo'],
                            'contrato.id_contrato_origem' => $auth->id_contrato
                        ])->order('contrato.id_contrato');

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

    /**
     * 
     * @param type $auth
     * @param type $nivel
     * @return type
     */
    public function listIndicador($auth) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select(['contrato1' => 'contrato'])
                        ->join(
                                ['contrato2' => 'contrato'], 'contrato1.id_contrato_origem = contrato2.id_contrato'
                        )
                        ->join(
                                'usuario', 'usuario.id_usuario = contrato2.id_usuario'
                        )
                        ->join(
                                'situacao_usuario', 'situacao_usuario.id_situacao_usuario = usuario.id_situacao_usuario'
                        )
                        ->join(
                                'email', 'email.id_email = usuario.id_email'
                        )
                        ->join(
                                'telefone', 'telefone.id_telefone = usuario.id_telefone'
                        )
                        ->where([
                            'contrato1.id_contrato' => $auth->id_contrato
                        ])->order('contrato1.id_contrato');

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

    /**
     * 
     * @param type $auth
     * @param type $nivel
     * @return type
     */
    public function listUniLevel($auth) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('usuario')
                        ->join(
                                'contrato', 'contrato.id_usuario = usuario.id_usuario'
                        )
                        ->join(
                                'contrato_as_contrato', 'contrato_as_contrato.id_contrato_filho = contrato.id_contrato'
                        )
                        ->join(
                                'situacao_usuario', 'situacao_usuario.id_situacao_usuario = usuario.id_situacao_usuario'
                        )
                        ->join(
                                'email', 'email.id_email = usuario.id_email'
                        )
                        ->join(
                                'telefone', 'telefone.id_telefone = usuario.id_telefone'
                        )
                        ->where([
                            'contrato_as_contrato.id_contrato' => $auth->id_contrato
                        ])->order('contrato.id_contrato');

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

    /**
     * 
     * @param type $auth
     * @return type
     */
    public function listUpLine($auth) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('contrato_as_contrato')
                ->join(
                        'contrato', 'contrato.id_contrato = contrato_as_contrato.id_contrato'
                )
                ->join(
                        'usuario', 'usuario.id_usuario = contrato.id_usuario'
                )
                ->join(
                        'email', 'email.id_email = usuario.id_email'
                )
                ->join(
                        'telefone', 'telefone.id_telefone = usuario.id_telefone'
                )
                ->where([
                    'contrato_as_contrato.id_contrato_filho' => $auth->id_contrato
                ])
                ->order('id_nivel')
                ->limit(1);

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

    /**
     * 
     * @param type $auth
     * @return type
     */
    public function listVideos($idEmpresa) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('empresa_as_video')
                ->where([
                    'empresa_as_video.id_situacao = ?' => $this->configList['situacao_ativo'],                    
                    'empresa_as_video.id_empresa' => $idEmpresa
                ])
                ->order('id_empresa_as_video');
        
        return $sql->prepareStatementForSqlObject($select)->execute();
    }

}
