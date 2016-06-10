<?php

namespace McNetwork\Controller;

use Estrutura\Controller\AbstractCrudController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class RelatorioController extends AbstractCrudController {

    /**
     * @var \McNetworko\Service\RedeService
     */
    protected $service;

    /**
     * @var \McNetwork\Form\RedeForm
     */
    protected $form;
    protected $storage;
    protected $configList;

    /**
     * 
     */
    public function __construct() {
        parent::init();
    }

    /**
     * 
     * @return type
     */
    public function indexAction() {

        $viewModel = new ViewModel([]);
        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     */
    public function relatorioPagamentosRecebidosAction() {

        $dtInicio = $this->getRequest()->getPost()->get('dt_inicio');
        $dtFim = $this->getRequest()->getPost()->get('dt_fim');
        $filter = $this->getFilterPage();

        $camposFilter = [
            '0' => NULL,
            '1' => [
                'filter' => "pagamento.id_pagamento LIKE ?",
            ],
            '2' => [
                'filter' => "DATE_FORMAT(pagamento.dt_mes_referencia,'%m/%Y') LIKE ?",
            ],
            '3' => [
                'filter' => "DATE_FORMAT(pagamento.dt_pagamento,'%d/%m/%Y') LIKE ?",
            ],
            '4' => [
                'filter' => "pagamento.vl_documento LIKE ?",
            ],
            '5' => [
                'filter' => "contrato.id_contrato LIKE ?",
                'mascara' => '\Estrutura\Helpers\Cript::decCod($value)',
            ],
            '6' => [
                'filter' => "LOWER(pagamento.ar_comprovante_pagamento) LIKE ?",
                'mascara' => 'strtolower($value)',
            ],
            '7' => [
                'filter' => "LOWER(usuario.nm_usuario) LIKE ?",
                'mascara' => 'strtolower($value)',
            ],
            '8' => NULL,
        ];

        $pagamentoService = $this->getServiceLocator()->get('\Pagamento\Service\PagamentoService');
        $paginator = $pagamentoService->getRelatorioPagamentosRecebidos($dtInicio, $dtFim, $filter, $camposFilter);

        $paginator->setItemCountPerPage($paginator->getTotalItemCount());

        $valorTotal = 0;
        foreach ($paginator as $extrato) {

            $valorTotal += $extrato['vl_pago'];
        }

        $countPerPage = $this->getCountPerPage(
                current(\Estrutura\Helpers\Pagination::getCountPerPage($paginator->getTotalItemCount()))
        );

        $paginator->setItemCountPerPage($this->getCountPerPage(
                        current(\Estrutura\Helpers\Pagination::getCountPerPage($paginator->getTotalItemCount()))
        ))->setCurrentPageNumber($this->getCurrentPage());

        $viewModel = new ViewModel([
            'valorTotal' => $valorTotal,
            'paginator' => $paginator,
            'dtInicio' => $dtInicio,
            'dtFim' => $dtFim,
            'filter' => $filter,
            'countPerPage' => $countPerPage,
            'camposFilter' => $camposFilter,
            'configList' => $this->getConfigList(),
        ]);

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     */
    public function relatorioBonusPagosAction() {

        $dtInicio = $this->getRequest()->getPost()->get('dt_inicio');
        $dtFim = $this->getRequest()->getPost()->get('dt_fim');

        $pagamentoService = $this->getServiceLocator()->get('\Pagamento\Service\PagamentoService');
        $listBonusPagos = $pagamentoService->toArrayResult($pagamentoService->getRelatorioBonusPagos($dtInicio, $dtFim));

        $viewModel = new ViewModel([
            'listBonusPagos' => $listBonusPagos,
            'dtInicio' => $dtInicio,
            'dtFim' => $dtFim,
        ]);

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     */
    public function relatorioSaquesPagosAction() {

        $dtInicio = $this->getRequest()->getPost()->get('dt_inicio');
        $dtFim = $this->getRequest()->getPost()->get('dt_fim');

        $filter = $this->getFilterPage();

        $camposFilter = [
            '0' => NULL,
            '1' => [
                'filter' => "pagamento.id_pagamento LIKE ?",
            ],
            '2' => [
                'filter' => "DATE_FORMAT(pagamento.dt_mes_referencia,'%m/%Y') LIKE ?",
            ],
            '3' => [
                'filter' => "DATE_FORMAT(pagamento.dt_pagamento,'%d/%m/%Y') LIKE ?",
            ],
            '4' => [
                'filter' => "pagamento.vl_documento LIKE ?",
            ],
            '5' => [
                'filter' => "contrato.id_contrato LIKE ?",
                'mascara' => '\Estrutura\Helpers\Cript::decCod($value)',
            ],
            '6' => [
                'filter' => "LOWER(usuario.nm_usuario) LIKE ?",
                'mascara' => 'strtolower($value)',
            ],
            '7' => NULL,
        ];

        $pagamentoService = $this->getServiceLocator()->get('\Pagamento\Service\PagamentoService');
        $paginator = $pagamentoService->getRelatorioSaquesPagos($dtInicio, $dtFim, $filter, $camposFilter);

        $paginator->setItemCountPerPage($paginator->getTotalItemCount());

        //Calcula o valor Total
        $valorTotal = 0;
        foreach ($paginator as $extrato) {

            $valorTotal += $extrato['vl_pago'];
        }

        $countPerPage = $this->getCountPerPage(
                current(\Estrutura\Helpers\Pagination::getCountPerPage($paginator->getTotalItemCount()))
        );

        $paginator->setItemCountPerPage($this->getCountPerPage(
                        current(\Estrutura\Helpers\Pagination::getCountPerPage($paginator->getTotalItemCount()))
        ))->setCurrentPageNumber($this->getCurrentPage());

        $viewModel = new ViewModel([
            'valorTotal' => $valorTotal,
            'paginator' => $paginator,
            'dtInicio' => $dtInicio,
            'dtFim' => $dtFim,
            'filter' => $filter,
            'countPerPage' => $countPerPage,
            'camposFilter' => $camposFilter,
        ]);

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     */
    public function relatorioCadastradosAction() {

        $dtInicio = $this->getRequest()->getPost()->get('dt_inicio');
        $dtFim = $this->getRequest()->getPost()->get('dt_fim');

        $usuarioService = $this->getServiceLocator()->get('\Usuario\Service\UsuarioService');
        $listCadastrados = $usuarioService->toArrayResult($usuarioService->getRelatorioCadastrados($dtInicio, $dtFim));

        $viewModel = new ViewModel([
            'listCadastrados' => $listCadastrados,
            'dtInicio' => $dtInicio,
            'dtFim' => $dtFim,
        ]);

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     */
    public function relatorioUnilevelAction() {

        $usuarioService = $this->getServiceLocator()->get('\Usuario\Service\UsuarioService');

        $idUsuario = \Estrutura\Helpers\Cript::dec($this->getRequest()->getPost()->get('id_usuario'));

        $list1 = $usuarioService->toArrayResult($usuarioService->getRelatorioUnilevel($idUsuario, 1));
        $list2 = $usuarioService->toArrayResult($usuarioService->getRelatorioUnilevel($idUsuario, 2));
        $list3 = $usuarioService->toArrayResult($usuarioService->getRelatorioUnilevel($idUsuario, 3));

        $viewModel = new ViewModel([
            'list1' => $list1,
            'list2' => $list2,
            'list3' => $list3,
            'configList' => $this->getConfigList(),
        ]);

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     */
    public function dadosPessoaisAction() {

        /* */
        $usuarioService = $this->getServiceLocator()->get('\Usuario\Service\UsuarioService');

        $form = new \McNetwork\Form\DadosPessoaisForm();

        $idUsuario = \Estrutura\Helpers\Cript::dec($this->getRequest()->getPost()->get('id_usuario'));

        $data = $usuarioService->getUsuario($idUsuario);

        $form->setData($data);

        $viewModel = new ViewModel([
            'idUsuario' => $idUsuario,
            'form' => $form,
            'configList' => $this->getConfigList(),
            'controller' => $this->params('controller'),
        ]);

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     * @return JsonModel
     * @throws \Exception
     */
    public function atualizarDadosAction() {
        try {

            $usuarioService = $this->getServiceLocator()->get('\Usuario\Service\UsuarioService');
            $form = new \McNetwork\Form\DadosPessoaisForm();

            $request = $this->getRequest();

            if (!$request->isPost()) {
                throw new \Exception('Dados Inválidos');
            }

            $post = \Estrutura\Helpers\Utilities::arrayMapArray('trim', $request->getPost()->toArray());

            if (!isset($post['id'])) {
                throw new \Exception('Dados Inválidos');
            }

            $post['id'] = \Estrutura\Helpers\Cript::dec($post['id']);

            if (!($post['id'])) {
                throw new \Exception('Dados Inválidos');
            }

            $usuarioEntity = $usuarioService->buscar($post['id']);

            $emailService = new \Email\Service\EmailService();
            $emailService->setEmEmail(trim($post['em_email']));
            $listEmails = $emailService->filtrarObjeto();
            if ($listEmails->count()) {
                if ($listEmails->current()->toArray()['id'] != $usuarioEntity->getIdEmail()) {
                    throw new \Exception('Dados Inválidos');
                }
            }

            $form->setData($post);

            if (!$form->isValid()) {

                throw new \Exception('Dados Inválidos');
            }

            $emailService = new \Email\Service\EmailService();
            $emailEntity = $emailService->buscar($usuarioEntity->getIdEmail());
            $emailEntity->setEmEmail(trim($post['em_email']));
            $emailEntity->salvar();

            $usuarioEntity->setNmUsuario(\Estrutura\Helpers\String::nomeMaiusculo($post['nm_usuario']));
            $usuarioEntity->setNuCpf(\Estrutura\Helpers\Cpf::cpfFilter($post['nu_cpf']));
            $usuarioEntity->salvar();

            return new JsonModel(
                    array(
                'status' => 'success',
                'message' => 'Salvo com sucesso',
                    )
            );
        } catch (\Exception $e) {

            return new JsonModel(
                    array(
                'status' => 'error',
                'message' => 'Dados inválidos',
                    )
            );
        }
    }

    /**
     * 
     */
    public function relatorioNotaUsuarioAction() {

        $idPagamento = \Estrutura\Helpers\Cript::dec($this->getRequest()->getPost()->get('idPagamento'));

        $pagamento = NULL;
        $usuario = NULL;

        if ($idPagamento) {

            $pagamentoService = $this->getServiceLocator()->get('\Pagamento\Service\PagamentoService');
            $pagamento = $pagamentoService->listUsuarioByIdPagamento($idPagamento);

            $usuarioService = $this->getServiceLocator()->get('\Usuario\Service\UsuarioService');
            $usuario = $usuarioService->getUsuario($pagamento['id_usuario']);
        }

        $viewModel = new ViewModel([
            'pagamento' => $pagamento,
            'usuario' => $usuario,
        ]);

        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     * @return type
     */
    public function cadastrarNotaAction() {


        $idPagamento = \Estrutura\Helpers\Cript::dec($this->getRequest()->getPost()->get('idPagamento'));

        if ($idPagamento) {

            $pagamentoService = new \Pagamento\Service\PagamentoService();
            $pagamentoEntity = $pagamentoService->buscar($idPagamento);
            $pagamentoEntity->setTxCodNotaFiscal(date('YmdHis'));
            $pagamentoEntity->salvar();
        }

        $viewModel = new ViewModel([]);
        return $viewModel->setTerminal(TRUE);
    }

    /**
     * 
     */
    public function relatorioSaldoUsuariosAction() {

        //Busca os usuarios cadastrados
        $usuarioService = new \Usuario\Service\UsuarioService();
        $listUsuarios = $usuarioService->filtrarObjeto();

        $listExtrato = [];
        $listContrato = [];

        foreach ($listUsuarios as $key => $usuarioEntity) {


            $pagamentoService = new \Pagamento\Service\PagamentoService();
            $pagamentoService->setConfigList($this->getConfigList());
            $listExtrato[$key] = $pagamentoService->toArrayResult($pagamentoService->getExtratoTeste($usuarioEntity->getId()));

            $contratoService = new \Contrato\Service\ContratoService();
            $contratoService->setIdUsuario($usuarioEntity->getId());
            $listContrato[$key] = current($contratoService->filtrarObjeto()->toArray());
        }

        $listPagamentosRecebidosPaginator = $pagamentoService->getRelatorioPagamentosRecebidos();
        $listPagamentosRecebidosPaginator->setItemCountPerPage($listPagamentosRecebidosPaginator->getTotalItemCount());

        $viewModel = new ViewModel([
            'listUsuarios' => $listUsuarios,
            'listExtrato' => $listExtrato,
            'listContrato' => $listContrato,
            'listPagamentosRecebidosPaginator' => $listPagamentosRecebidosPaginator,
            'configList' => $this->getConfigList(),
        ]);

        return $viewModel->setTerminal(TRUE);
    }

}
