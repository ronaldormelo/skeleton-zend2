<?php

namespace Usuario\Service;

use \Usuario\Entity\UsuarioEntity as Entity;

class UsuarioService extends Entity {

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
     * @param type $nivel
     * @return type
     */
    public function getUsuario($id) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select('usuario')
                ->join(
                        'contrato', 'contrato.id_usuario = usuario.id_usuario'
                )
                ->join(
                        'conta_bancaria', 'conta_bancaria.id_usuario = usuario.id_usuario', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'estado_civil', 'estado_civil.id_estado_civil = usuario.id_estado_civil', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
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
                        'endereco', 'endereco.id_endereco = usuario.id_endereco', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'cidade', 'cidade.id_cidade = endereco.id_cidade', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'estado', 'estado.id_estado = cidade.id_estado', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'banco', 'banco.id_banco = conta_bancaria.id_banco', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'tipo_conta', 'tipo_conta.id_tipo_conta = conta_bancaria.id_tipo_conta', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->where([
            'usuario.id_usuario = ?' => $id,
        ]);

        return $sql->prepareStatementForSqlObject($select)->execute()->current();
    }

    /**
     * 
     * @param type $auth
     * @param type $nivel
     * @return type
     */
    public function getUsuarioAtivo($id) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select('usuario')
                ->join(
                        'contrato', 'contrato.id_usuario = usuario.id_usuario'
                )
                ->join(
                        'conta_bancaria', 'conta_bancaria.id_usuario = usuario.id_usuario', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'estado_civil', 'estado_civil.id_estado_civil = usuario.id_estado_civil', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
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
                        'endereco', 'endereco.id_endereco = usuario.id_endereco', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'cidade', 'cidade.id_cidade = endereco.id_cidade', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'estado', 'estado.id_estado = cidade.id_estado', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'banco', 'banco.id_banco = conta_bancaria.id_banco', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'tipo_conta', 'tipo_conta.id_tipo_conta = conta_bancaria.id_tipo_conta', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->where([
            'usuario.id_usuario = ?' => $id,
            'usuario.id_situacao_usuario = ?' => $this->configList['situacao_usuario_ativo'],
        ]);

        return $sql->prepareStatementForSqlObject($select)->execute()->current();
    }

    /**
     * 
     * @return type
     */
    public function getIdProximoUsuarioCadastro($configList) {

        //Busca os usuarios cadastrados
        $usuarioService = $this->getServiceLocator()->get('Usuario\Service\UsuarioService');
        $resultSetUsuarios = $usuarioService->filtrarObjeto();

        /* @var $contratoAsContratoService \ContratoAsContrato\Service\ContratoAsContratoService */
        $contratoAsContratoService = $this->getServiceLocator()->get('\ContratoAsContrato\Service\ContratoAsContratoService');

        foreach ($resultSetUsuarios as $usuarioEntity) {

            /* @var $contratoService \Contrato\Service\ContratoService */
            $contratoService = $this->getServiceLocator()->get("\Contrato\Service\ContratoService");
            $contratoService->setIdUsuario($usuarioEntity->getId());
            $contrato = $contratoService->filtrarObjeto()->current();

            $contratoAsContratoService->setIdContrato($contrato->getId());
            $contratoAsContratoService->setIdNivel(1);


            if ($contratoAsContratoService->filtrarObjeto()->count() < $configList['qtd_por_nivel']) {

                return $usuarioEntity->getId();
            }
        }
        return NULL;
    }

    /**
     * 
     * @param type $dtInicio
     * @param type $dtFim
     * @return type
     */
    public function getRelatorioCadastrados($dtInicio, $dtFim) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('usuario')->columns([
                    'id_usuario',
                    'nm_usuario'
                ])
                ->join('contrato', 'usuario.id_usuario = contrato.id_usuario', [
                    'id_contrato',
                    'dt_adesao',
                ])
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
                        'endereco', 'endereco.id_endereco = usuario.id_endereco', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                        'cidade', 'cidade.id_cidade = endereco.id_cidade', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
                )
                ->join(
                'estado', 'estado.id_estado = cidade.id_estado', \Zend\Db\Sql\Select::SQL_STAR, \Zend\Db\Sql\Select::JOIN_LEFT
        );

        $where = [];

        if ($dtInicio && $dtFim) {

            $dtInicio = \DateTime::createFromFormat('d/m/Y', $dtInicio);
            $dtFim = \DateTime::createFromFormat('d/m/Y', $dtFim);
            $dtFim->modify('+24 hour');
            $where[] = new \Zend\Db\Sql\Predicate\Expression("contrato.dt_adesao BETWEEN '" . $dtInicio->format('Y-m-d') . " 00:00:00' AND '" . $dtFim->format('Y-m-d') . " 00:00:00'");
        } elseif ($dtInicio) {

            $dtInicio = \DateTime::createFromFormat('d/m/Y', $dtInicio);
            $where['contrato.dt_adesao >= ?'] = $dtInicio->format('Y-m-d') . ' 00:00:00';
        } elseif ($dtFim) {

            $dtFim = \DateTime::createFromFormat('d/m/Y', $dtFim);
            $dtFim->modify('+24 hour');
            $where['contrato.dt_adesao <= ?'] = $dtFim->format('Y-m-d') . ' 00:00:00';
        }

        $select->where($where)->order(['contrato.dt_adesao DESC']);
        return $sql->prepareStatementForSqlObject($select)->execute();
    }

    /**
     * 
     * @param type $auth
     * @param type $nivel
     * @return type
     */
    public function getRelatorioUnilevel($idUsuario, $nivel) {
        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select1 = $sql->select('contrato')
                ->columns(['id_contrato'])
                ->where([
            'contrato.id_usuario = ?' => $idUsuario,
        ]);

        $select2 = $sql->select('usuario')
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
            new \Zend\Db\Sql\Predicate\In('contrato_as_contrato.id_contrato', $select1)
        ]);
        $select2->order('id_contratos_as_contratos');

        return $sql->prepareStatementForSqlObject($select2)->execute();
    }

    /**
     * 
     * @param type $userLogin
     * @param type $userPass
     * @param type $userNicename
     * @param type $userEmail
     * @param type $userUrl
     * @param type $userRegistered
     * @param type $userActivationKey
     * @param type $userStatus
     * @param type $displayName
     * @return type
     */
    public function cadastraMcUsers(
    $userLogin, $userPass, $userNicename, $userEmail, $userUrl, $userRegistered, $userActivationKey, $userStatus, $displayName
    ) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $insert = $sql->insert('mc_users');
        $insert->values([
            'user_login' => trim($userLogin),
            'user_pass' => $userPass,
            'user_nicename' => trim(str_replace('@', '', $userNicename)),
            'user_email' => trim($userEmail),
            'user_url' => $userUrl,
            'user_registered' => $userRegistered,
            'user_activation_key' => $userActivationKey,
            'user_status' => $userStatus,
            'display_name' => $displayName
        ]);

        $sql->prepareStatementForSqlObject($insert)->execute();
        return $sql->getAdapter()->getDriver()->getLastGeneratedValue();
    }

    /**
     * 
     * @param type $fieldId
     * @param type $userId
     * @param type $value
     * @param type $lastUpdated
     * @return type
     */
    public function cadastraMcBpXprofileData(
    $fieldId, $userId, $value, $lastUpdated
    ) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $insert = $sql->insert('mc_bp_xprofile_data');
        $insert->values([
            'field_id' => $fieldId,
            'user_id' => $userId,
            'value' => $value,
            'last_updated' => $lastUpdated
        ]);

        $sql->prepareStatementForSqlObject($insert)->execute();
        return $sql->getAdapter()->getDriver()->getLastGeneratedValue();
    }

    /**
     * 
     * @param type $fieldId
     * @param type $userId
     * @param type $value
     * @param type $lastUpdated
     * @return type
     */
    public function cadastraMcUsermeta(
    $userId, $metaKey, $metaValue
    ) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $insert = $sql->insert('mc_usermeta');
        $insert->values([
            'user_id' => $userId,
            'meta_key' => $metaKey,
            'meta_value' => $metaValue
        ]);

        $sql->prepareStatementForSqlObject($insert)->execute();
        return $sql->getAdapter()->getDriver()->getLastGeneratedValue();
    }

    /**
     * 
     * @return type
     */
    public function getUsuariosIndicou1($configList) {

        $sql = 'SELECT
                usuario.nm_usuario,
                contrato.id_contrato,
                contrato.id_usuario,
                contrato.dt_pagamento,
                contrato.qtd,
                telefone.nr_ddd_telefone,
                telefone.nr_telefone,
                email.em_email
                FROM (
                     SELECT
                     con.id_contrato AS id_contrato,
                     con.id_usuario AS id_usuario,
                     MIN(pag.dt_pagamento) AS dt_pagamento,
                     (
                     SELECT COUNT(contrato2.id_contrato) AS qtd
                     FROM contrato AS contrato2
                     INNER JOIN usuario AS usuario2 ON usuario2.id_usuario = contrato2.id_usuario
                     WHERE contrato2.id_contrato_origem = con.id_contrato
                     AND usuario2.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                     ) AS qtd
                FROM contrato con
                JOIN pagamento pag ON pag.id_contrato = con.id_contrato AND pag.id_tipo_pagamento = ' . $configList['tipo_pagamento_mensalidade'] . ' AND pag.id_situacao_pagamento = ' . $configList['situacao_pagamento_pago'] . '
                GROUP BY con.id_contrato,
                con.id_usuario,
                qtd
                ) AS contrato
                INNER JOIN usuario ON usuario.id_usuario = contrato.id_usuario
                INNER JOIN telefone ON telefone.id_telefone = usuario.id_telefone
                INNER JOIN email ON email.id_email = usuario.id_email
                WHERE contrato.dt_pagamento < DATE_SUB(CURDATE(), INTERVAL 7 DAY)    
                AND contrato.qtd = 1                
                AND usuario.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                AND WEEKDAY(CURDATE()) = 0
                ORDER BY id_contrato';

        $adapter = $this->getAdapter();
        return $adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * 
     * @return type
     */
    public function getUsuariosIndicou2($configList) {

        $sql = 'SELECT
                usuario.nm_usuario,
                contrato.id_contrato,
                contrato.id_usuario,
                contrato.dt_pagamento,
                contrato.qtd,
                telefone.nr_ddd_telefone,
                telefone.nr_telefone,
                email.em_email
                FROM (
                     SELECT
                     con.id_contrato AS id_contrato,
                     con.id_usuario AS id_usuario,
                     MIN(pag.dt_pagamento) AS dt_pagamento,
                     (
                     SELECT COUNT(contrato2.id_contrato) AS qtd
                     FROM contrato AS contrato2
                     INNER JOIN usuario AS usuario2 ON usuario2.id_usuario = contrato2.id_usuario
                     WHERE contrato2.id_contrato_origem = con.id_contrato
                     AND usuario2.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                     ) AS qtd
                FROM contrato con
                JOIN pagamento pag ON pag.id_contrato = con.id_contrato AND pag.id_tipo_pagamento = ' . $configList['tipo_pagamento_mensalidade'] . ' AND pag.id_situacao_pagamento = ' . $configList['situacao_pagamento_pago'] . '
                GROUP BY con.id_contrato,
                con.id_usuario,
                qtd
                ) AS contrato
                INNER JOIN usuario ON usuario.id_usuario = contrato.id_usuario
                INNER JOIN telefone ON telefone.id_telefone = usuario.id_telefone
                INNER JOIN email ON email.id_email = usuario.id_email
                WHERE contrato.dt_pagamento < DATE_SUB(CURDATE(), INTERVAL 7 DAY)                
                AND contrato.qtd = 2 
                AND usuario.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                AND WEEKDAY(CURDATE()) = 0
                ORDER BY id_contrato';

        $adapter = $this->getAdapter();
        return $adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * 
     * @return type
     */
    public function getUsuariosIndicou3($configList) {

        $sql = 'SELECT
                usuario.nm_usuario,
                contrato.id_contrato,
                contrato.id_usuario,
                contrato.dt_pagamento,
                contrato.qtd,
                telefone.nr_ddd_telefone,
                telefone.nr_telefone,
                email.em_email
                FROM (
                     SELECT
                     con.id_contrato AS id_contrato,
                     con.id_usuario AS id_usuario,
                     MIN(pag.dt_pagamento) AS dt_pagamento,
                     (
                     SELECT COUNT(contrato2.id_contrato) AS qtd
                     FROM contrato AS contrato2
                     INNER JOIN usuario AS usuario2 ON usuario2.id_usuario = contrato2.id_usuario
                     WHERE contrato2.id_contrato_origem = con.id_contrato
                     AND usuario2.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                     ) AS qtd
                FROM contrato con
                JOIN pagamento pag ON pag.id_contrato = con.id_contrato AND pag.id_tipo_pagamento = ' . $configList['tipo_pagamento_mensalidade'] . ' AND pag.id_situacao_pagamento = ' . $configList['situacao_pagamento_pago'] . '
                GROUP BY con.id_contrato,
                con.id_usuario,
                qtd
                ) AS contrato
                INNER JOIN usuario ON usuario.id_usuario = contrato.id_usuario
                INNER JOIN telefone ON telefone.id_telefone = usuario.id_telefone
                INNER JOIN email ON email.id_email = usuario.id_email
                WHERE contrato.dt_pagamento < DATE_SUB(CURDATE(), INTERVAL 7 DAY)                
                AND contrato.qtd = 3
                AND usuario.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                AND WEEKDAY(CURDATE()) = 0
                ORDER BY id_contrato';

        $adapter = $this->getAdapter();
        return $adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * 
     * @return type
     */
    public function getUsuariosIndicou4($configList) {

        $sql = 'SELECT
                usuario.nm_usuario,
                contrato.id_contrato,
                contrato.id_usuario,
                contrato.dt_pagamento,
                contrato.qtd,
                telefone.nr_ddd_telefone,
                telefone.nr_telefone,
                email.em_email
                FROM (
                     SELECT
                     con.id_contrato AS id_contrato,
                     con.id_usuario AS id_usuario,
                     MIN(pag.dt_pagamento) AS dt_pagamento,
                     (
                     SELECT COUNT(contrato2.id_contrato) AS qtd
                     FROM contrato AS contrato2
                     INNER JOIN usuario AS usuario2 ON usuario2.id_usuario = contrato2.id_usuario
                     WHERE contrato2.id_contrato_origem = con.id_contrato
                     AND usuario2.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                     ) AS qtd
                FROM contrato con
                JOIN pagamento pag ON pag.id_contrato = con.id_contrato AND pag.id_tipo_pagamento = ' . $configList['tipo_pagamento_mensalidade'] . ' AND pag.id_situacao_pagamento = ' . $configList['situacao_pagamento_pago'] . '
                GROUP BY con.id_contrato,
                con.id_usuario,
                qtd
                ) AS contrato
                INNER JOIN usuario ON usuario.id_usuario = contrato.id_usuario
                INNER JOIN telefone ON telefone.id_telefone = usuario.id_telefone
                INNER JOIN email ON email.id_email = usuario.id_email
                WHERE contrato.dt_pagamento < DATE_SUB(CURDATE(), INTERVAL 7 DAY)                
                AND contrato.qtd = 4
                AND usuario.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                AND WEEKDAY(CURDATE()) = 0
                ORDER BY id_contrato';

        $adapter = $this->getAdapter();
        return $adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * 
     * @return type
     */
    public function getUsuariosIndicou5($configList) {

        $sql = 'SELECT
                usuario.nm_usuario,
                contrato.id_contrato,
                contrato.id_usuario,
                contrato.dt_pagamento,
                contrato.qtd,
                telefone.nr_ddd_telefone,
                telefone.nr_telefone,
                email.em_email
                FROM (
                     SELECT
                     con.id_contrato AS id_contrato,
                     con.id_usuario AS id_usuario,
                     MIN(pag.dt_pagamento) AS dt_pagamento,
                     (
                     SELECT COUNT(contrato2.id_contrato) AS qtd
                     FROM contrato AS contrato2
                     INNER JOIN usuario AS usuario2 ON usuario2.id_usuario = contrato2.id_usuario
                     WHERE contrato2.id_contrato_origem = con.id_contrato
                     AND usuario2.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                     ) AS qtd
                FROM contrato con
                JOIN pagamento pag ON pag.id_contrato = con.id_contrato AND pag.id_tipo_pagamento = ' . $configList['tipo_pagamento_mensalidade'] . ' AND pag.id_situacao_pagamento = ' . $configList['situacao_pagamento_pago'] . '
                GROUP BY con.id_contrato,
                con.id_usuario,
                qtd
                ) AS contrato
                INNER JOIN usuario ON usuario.id_usuario = contrato.id_usuario
                INNER JOIN telefone ON telefone.id_telefone = usuario.id_telefone
                INNER JOIN email ON email.id_email = usuario.id_email
                WHERE contrato.dt_pagamento < DATE_SUB(CURDATE(), INTERVAL 7 DAY)                
                AND contrato.qtd >= 5
                AND usuario.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                AND WEEKDAY(CURDATE()) = 0
                ORDER BY id_contrato';

        $adapter = $this->getAdapter();
        return $adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * 
     * @return type
     */
    public function getUsuariosIndicouMais5($configList) {

        $sql = 'SELECT
                usuario.nm_usuario,
                contrato.id_contrato,
                contrato.id_usuario,
                contrato.dt_pagamento,
                contrato.qtd,
                telefone.nr_ddd_telefone,
                telefone.nr_telefone,
                email.em_email
                FROM (
                     SELECT
                     con.id_contrato AS id_contrato,
                     con.id_usuario AS id_usuario,
                     MIN(pag.dt_pagamento) AS dt_pagamento,
                     (
                     SELECT COUNT(contrato2.id_contrato) AS qtd
                     FROM contrato AS contrato2
                     INNER JOIN usuario AS usuario2 ON usuario2.id_usuario = contrato2.id_usuario
                     WHERE contrato2.id_contrato_origem = con.id_contrato
                     AND usuario2.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                     ) AS qtd
                FROM contrato con
                JOIN pagamento pag ON pag.id_contrato = con.id_contrato AND pag.id_tipo_pagamento = ' . $configList['tipo_pagamento_mensalidade'] . ' AND pag.id_situacao_pagamento = ' . $configList['situacao_pagamento_pago'] . '
                GROUP BY con.id_contrato,
                con.id_usuario,
                qtd
                ) AS contrato
                INNER JOIN usuario ON usuario.id_usuario = contrato.id_usuario
                INNER JOIN telefone ON telefone.id_telefone = usuario.id_telefone
                INNER JOIN email ON email.id_email = usuario.id_email
                WHERE contrato.dt_pagamento < DATE_SUB(CURDATE(), INTERVAL 7 DAY)                
                AND contrato.qtd > 5
                AND usuario.id_situacao_usuario = ' . $configList['situacao_usuario_ativo'] . '
                AND WEEKDAY(CURDATE()) = 0
                ORDER BY id_contrato';

        $adapter = $this->getAdapter();
        return $adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * 
     * @return type
     */
    public function getValorArrecadadoDivisao($configList) {

        $sql = 'SELECT 
                IFNULL(ROUND(SUM(IFNULL(pag.vl_pago, 0)), 2), 0) AS valor_arrecadado_para_divisao
                FROM pagamento pag 
                WHERE pag.id_tipo_pagamento = ' . $configList['tipo_pagamento_mensalidade'] . '
                AND pag.id_situacao_pagamento = ' . $configList['situacao_pagamento_pago'] . '
                AND (
                        pag.dt_pagamento > DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                        AND 
                        pag.dt_pagamento < CURDATE()
                )
                AND WEEKDAY(CURDATE()) = 0
                ORDER BY dt_pagamento';

        $adapter = $this->getAdapter();
        return $adapter->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * 
     * @param type $dtInicio
     * @param type $dtFim
     * @return type
     */
    public function getUsuarioWP($email) {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('mc_users');

        $where = [
            "mc_users.user_login = '" . trim($email) . "'",
        ];

        $select->where($where);
        return $sql->prepareStatementForSqlObject($select)->execute()->current();
    }

}
