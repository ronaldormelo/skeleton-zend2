<?php

namespace McNetwork\Controller;

use Estrutura\Controller\AbstractEstruturaController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractEstruturaController {

    /**
     * 
     * @return ViewModel
     */
    public function indexAction() {
        
        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();

        /* @var $pagamentoService \Pagamento\Service\PagamentoService */
        $pagamentoService = $this->getServiceLocator()->get("Pagamento\Service\PagamentoService");
        
        if ($auth->id_perfil == $this->getConfigList()['perfil_administrador']) {
            
            $pagamentoService->createPagamento();
        } else {
            
            $pagamentoService->createPagamento($auth->id_usuario);
        }        
        
        $comunicadoService = new \Comunicado\Service\ComunicadoService();
        $comunicadoService->setConfigList($this->getConfigList());
        $comunicados = $comunicadoService->toArrayResult($comunicadoService->fetchAllAtivos());

        /* @var $contratoService \Contrato\Service\ContratoService */
        $contratoService = new \Contrato\Service\ContratoService();
        $contratoService->setConfigList($this->getConfigList());
        $contratoEntity = $contratoService->buscar($auth->id_contrato);

        $meusGanhosList = $pagamentoService->toArrayResult($pagamentoService->getMeusGanhos($auth));
        
        $mensalidadeFoiPaga = $pagamentoService->mensalidadeFoiPaga($auth);

        return new ViewModel([
            'configList' => $this->getConfigList(),
            'auth' => $auth,
            'contratoEntity' => $contratoEntity,
            'meusGanhosList' => $meusGanhosList,
            'mensalidadeFoiPaga' => $mensalidadeFoiPaga,            
            'comunicados' => $comunicados,            
        ]);
    }

    /**
     * 
     */
    public function infoAction() {

        phpinfo();
        die;
    }
}
