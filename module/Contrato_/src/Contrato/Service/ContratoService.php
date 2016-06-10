<?php

namespace Contrato\Service;

use \Contrato\Entity\ContratoEntity as Entity;

class ContratoService extends Entity {

    /**
     *
     * @var type 
     */
    protected $configList;

    /**
     * 
     * @param type $configList
     */
    public function setConfigList($configList)
    {
        $this->configList = $configList;
    }

    public function listContratoFilhoUsuario($idUsuario)
    {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('contrato')
                ->join(
                        'contrato_as_contrato', 'contrato_as_contrato.id_contrato_filho = contrato.id_contrato'
                )
                ->join(
                        'nivel', 'nivel.id_nivel = contrato_as_contrato.id_nivel'
                )
                ->where([
            'contrato.id_usuario = ?' => $idUsuario,
        ]);

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

    public function listContratosPendentes()
    {

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());

        $select = $sql->select('contrato')
                ->join(
                        'usuario', 'usuario.id_usuario = contrato.id_usuario'
                )
                ->join(
                        'login', 'login.id_usuario = usuario.id_usuario'
                )
                ->join(
                        'email', 'email.id_email = usuario.id_email'
                )
                ->join(
                        'telefone', 'telefone.id_telefone = usuario.id_telefone'
                )
                ->where([
            'usuario.id_situacao_usuario = ?' => $this->configList['situacao_usuario_atrasado'],
            'login.id_perfil != ?' => $this->configList['perfil_administrador'],
        ]);

        return $sql->prepareStatementForSqlObject($select)->execute();
    }

    /**
     * 
     * @param type $id_contrato
     */
    public function excluirContrato($id_contrato)
    {

        //Atualiza as solicitações de patrocinador desse contrato
        $solicitacaoEmpresaService = new \SolicitacaoEmpresa\Service\SolicitacaoEmpresaService();
        $solicitacaoEmpresaService->setIdContratoSolicitado($id_contrato);
        $solicitacaoEmpresas = $solicitacaoEmpresaService->filtrarObjeto();

        if (!empty($solicitacaoEmpresas)) {

            $contratoService = new \Contrato\Service\ContratoService();
            $contratoEntity = $contratoService->buscar($id_contrato);

            foreach ($solicitacaoEmpresas as $solicitacaoEmpresaEntity) {

                $contratoSolicitanteEntity = $contratoService->buscar($solicitacaoEmpresaEntity->getIdContratoSolicitante());
                $idEmpresa = $solicitacaoEmpresaEntity->getIdEmpresa();

                $contratoAsContratoService = new \ContratoAsContrato\Service\ContratoAsContratoService();
                $contratoAsContratoService->setConfigList($this->configList);

                $solicitacaoEmpresaEntity->excluir();
                $contratoEntitySolicitado = $contratoAsContratoService->solicitarPatrocinador($contratoEntity, $idEmpresa, $contratoSolicitanteEntity);

                $empresaAsContratoService = new \EmpresaAsContrato\Service\EmpresaAsContratoService();
                $empresaAsContratoService->setIdContratoPai($id_contrato);
                $empresaAsContratos = $empresaAsContratoService->filtrarObjeto();

                if (!empty($empresaAsContratos)) {
                    foreach ($empresaAsContratos as $empresaAsContratoEntity) {

                        $empresaAsContratoEntity->setsetIdContratoPai($contratoEntitySolicitado->getId());
                        $empresaAsContratoEntity->salvar();
                    }
                }
            }
        }

        //Exclui as empresas_contrato desse contrato
        $empresaAsContratoService = new \EmpresaAsContrato\Service\EmpresaAsContratoService();
        $empresaAsContratoService->setIdContrato($id_contrato);
        $empresaAsContratos = $empresaAsContratoService->filtrarObjeto();

        if (!empty($empresaAsContratos)) {
            foreach ($empresaAsContratos as $empresaAsContratoEntity) {

                $empresaAsContratoEntity->excluir();
            }
        }

        //Exclui Solicitações do contrato
        $solicitacaoEmpresaService = new \SolicitacaoEmpresa\Service\SolicitacaoEmpresaService();
        $solicitacaoEmpresaService->setIdContratoSolicitante($id_contrato);
        $solicitacaoEmpresas = $solicitacaoEmpresaService->filtrarObjeto();

        if (!empty($solicitacaoEmpresas)) {
            foreach ($solicitacaoEmpresas as $solicitacaoEmpresaEntity) {

                $solicitacaoEmpresaEntity->excluir();
            }
        }

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());
        $select1 = $sql->select('contrato_as_contrato')
                        ->columns(['id_contrato'])
                        ->where([
                            'contrato_as_contrato.id_contrato_filho = ?' => $id_contrato,
                        ])->order(['id_nivel DESC']);

        $resultSet1 = $sql->prepareStatementForSqlObject($select1)->execute();

        $id_contrato_niveis = [];
        $id_contrato_indicacao = NULL;
        if (!empty($resultSet1)) {
            foreach ($resultSet1 as $key => $result1) {

                $id_contrato_indicacao = $result1['id_contrato'];
                $id_contrato_niveis[$key] = $result1['id_contrato'];
            }
        }

        $limpaPai = FALSE;
        if (!empty($id_contrato_niveis)) {
            foreach ($id_contrato_niveis as $key => $id_contrato_nivel) {

                if (!$limpaPai) {

                    $limpaPai = TRUE;

                    $select2 = $sql->select('contrato_as_contrato')
                            ->columns(['id_contrato_filho'])
                            ->where([
                        'contrato_as_contrato.id_contrato = ?' => $id_contrato,
                    ]);

                    $select3 = $sql->select('contrato_as_contrato')
                            ->columns(['id_contratos_as_contratos'])
                            ->where([
                        new \Zend\Db\Sql\Predicate\In('contrato_as_contrato.id_contrato_filho', $select2),
                        'id_contrato = ?' => $id_contrato_nivel,
                    ]);

                    $resultSet2 = $sql->prepareStatementForSqlObject($select3)->execute();

                    if (!empty($resultSet2)) {
                        foreach ($resultSet2 as $result2) {

                            $delete1 = $sql->delete('contrato_as_contrato');
                            $delete1->where([
                                'id_contratos_as_contratos = ?' => $result2['id_contratos_as_contratos']
                            ]);

                            $sql->prepareStatementForSqlObject($delete1)->execute();
                        }
                    }
                }


                $select4 = $sql->select('contrato_as_contrato')
                        ->columns(['id_contrato_filho'])
                        ->where([
                    'contrato_as_contrato.id_contrato = ?' => $id_contrato,
                ]);

                $select5 = $sql->select('contrato_as_contrato')
                        ->columns(['id_contratos_as_contratos'])
                        ->where([
                    new \Zend\Db\Sql\Predicate\In('contrato_as_contrato.id_contrato_filho', $select4),
                    'id_contrato = ?' => isset($id_contrato_niveis[$key + 1]) ? $id_contrato_niveis[$key + 1] : $id_contrato,
                ]);

                $resultSet3 = $sql->prepareStatementForSqlObject($select5)->execute();

                if (!empty($resultSet3)) {
                    foreach ($resultSet3 as $result3) {

                        $update1 = $sql->update('contrato_as_contrato');
                        $update1->set([
                            'id_contrato' => $id_contrato_nivel
                        ])->where([
                            'id_contratos_as_contratos = ?' => $result3['id_contratos_as_contratos']
                        ]);

                        $sql->prepareStatementForSqlObject($update1)->execute();
                    }
                }
            }
        }

        $delete2 = $sql->delete('contrato_as_contrato');
        $delete2->where([
            'id_contrato_filho' => $id_contrato
        ]);
        $sql->prepareStatementForSqlObject($delete2)->execute();

        if ($id_contrato_indicacao) {

            $update2 = $sql->update('contrato_as_contrato');
            $update2->set([
                'id_contrato_indicacao' => $id_contrato_indicacao
            ])->where([
                'id_contrato_indicacao = ?' => $id_contrato
            ]);

            $sql->prepareStatementForSqlObject($update2)->execute();
        }

        //Altera a situação do contrato do usuário excluido
        $select4 = $sql->select('contrato')
                ->join('usuario', 'contrato.id_usuario = usuario.id_usuario')
                ->where([
            'contrato.id_contrato = ?' => $id_contrato,
        ]);

        $resultSet2 = $sql->prepareStatementForSqlObject($select4)->execute()->current();
        //Busca os usuarios cadastrados
        /* @var $usuarioService Usuario\Service\UsuarioService */
        $usuarioService = $this->getServiceLocator()->get('Usuario\Service\UsuarioService');
        $usuarioEntity = $usuarioService->buscar($resultSet2['id_usuario']);
        $usuarioEntity->setIdSituacaoUsuario($this->configList['situacao_usuario_congelado']);
        $usuarioEntity->salvar();
    }

}
