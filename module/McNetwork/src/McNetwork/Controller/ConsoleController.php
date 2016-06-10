<?php

namespace McNetwork\Controller;

use Estrutura\Controller\AbstractCrudController;
use Zend\View\Model\ViewModel;
use Estrutura\Helpers\Cript;

class ConsoleController extends AbstractCrudController {

    /**
     * @var \McNetworko\Service\CursosService
     */
    protected $service;

    /**
     * @var \McNetwork\Form\CursosForm
     */
    protected $form;

    /**
     *
     * @var type 
     */
    protected $storage;

    /**
     *
     * @var type 
     */
    protected $configList;

    /**
     * 
     */
    public function __construct() {
        parent::init();
    }

    /**
     * php index.php excluir usuarios congelados
     * @return type
     */
    public function excluirUsuariosCongeladosAction() {


        /* @var $pagamentoService \Pagamento\Service\PagamentoService */
        $pagamentoService = $this->getServiceLocator()->get("Pagamento\Service\PagamentoService");
        $pagamentoService->excluirUsuariosCongelados();
    }

    /**
     * php index.php zerar saldo usuarios
     * @return type
     */
    public function zerarSaldoUsuariosAction() {

        //Busca os usuarios cadastrados
        $usuarioService = new \Usuario\Service\UsuarioService();
        $listUsuarios = $usuarioService->filtrarObjeto();

        $listContrato = [];

        foreach ($listUsuarios as $key => $usuarioEntity) {

            $contratoService = new \Contrato\Service\ContratoService();
            $contratoService->setIdUsuario($usuarioEntity->getId());
            $listContrato[$key] = current($contratoService->filtrarObjeto()->toArray());
            
            $vlSaldo = isset($listContrato[$key]['vl_saldo']) ? $listContrato[$key]['vl_saldo'] : 0;
            
            if ($vlSaldo > 0) {
                
                //grava os dados
                $pagamentoEntityFilho = new \Pagamento\Service\PagamentoService();
                $pagamentoEntityFilho->exchangeArray([
                    'id' => NULL,
                    'dt_vencimento' => date('Y-m-d H:i:s'),
                    'vl_documento' => $vlSaldo,
                    'dt_pagamento' => date('Y-m-d H:i:s'),
                    'vl_pago' => $vlSaldo,
                    'ar_comprovante_pagamento' => 'Saldo expirado',
                    'id_tipo_pagamento' => $this->getConfigList()['tipo_pagamento_expiracao'],
                    'id_contrato' => $listContrato[$key]['id_contrato'],
                    'id_situacao_pagamento' => $this->getConfigList()['situacao_pagamento_pago'],
                    'dt_mes_referencia' => date('Y-m-d H:i:s'),
                ]);
                $pagamentoEntityFilho->salvar();

                $contratoService = new \Contrato\Service\ContratoService();
                $contratoEntity = $contratoService->buscar($listContrato[$key]['id_contrato']);
                $contratoEntity->setVlSaldo($contratoEntity->getVlSaldo() - $vlSaldo);
                $contratoEntity->salvar();
            }                        
        }   
        
        echo 'Saldo dos usuários excluídos com sucesso.';
        
    }

}
