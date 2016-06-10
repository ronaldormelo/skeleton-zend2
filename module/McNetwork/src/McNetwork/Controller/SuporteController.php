<?php

namespace McNetwork\Controller;

use Estrutura\Controller\AbstractCrudController;
use Zend\View\Model\ViewModel;
use Estrutura\Helpers\Cript;
use Zend\View\Model\JsonModel;

class SuporteController extends AbstractCrudController {

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
     * 
     * @return type
     */
    public function listAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();


        $empresaAsVideoService = new \EmpresaAsVideo\Service\EmpresaAsVideoService();
        $empresaAsVideoService->setConfigList($this->getConfigList());
        $listVideos = $empresaAsVideoService->toArrayResult($empresaAsVideoService->listEmpresasVideos($auth->id_contrato));
        
        $empresaService = new \Empresa\Service\EmpresaService();
        $empresaService->setConfigList($this->getConfigList());
        $listEmpresas = $empresaService->toArrayResult($empresaService->listEmpresasSuporte($auth->id_contrato));
        $empresas = [];
        if (!empty($listEmpresas)) {
            foreach ($listEmpresas as $empresa) {
                $empresas[] = \Estrutura\Helpers\Cript::enc($empresa['id_empresa']);
            }
        }
        $form = new \McNetwork\Form\SuporteForm(['empresas' => $empresas]);
        
        $dadosView = [
            'form' => $form,
            'listEmpresas' => $listEmpresas,
            'listVideos' => $listVideos,
            'controller' => $this->params('controller'),
            'atributos' => [],
            'configList' => $this->getConfigList(),
            'mensalidadeFoiPaga' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->mensalidadeFoiPaga($auth),
            'liberaPagamentoMensalidade' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->liberaPagamentoMensalidade($auth),
        ];


        $view = new ViewModel($dadosView);
        return $view->setTerminal(true);
    }

    /**
     * 
     * @return type
     */
    public function courseInformationAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();
        $cursosService = new \McNetwork\Service\CursosService();
        $cursosService->setIdSituacao($this->getConfigList()['situacao_ativo']);
        $list = $cursosService->filtrarObjeto();

        $loginService = new \Login\Service\LoginService();
        $loginService->setIdUsuario($auth->id_usuario);
        $loginEntity = $loginService->filtrarObjeto()->current();

        $loginAtivo = FALSE;
        if ($loginEntity->getIdSituacao() == $this->getConfigList()['situacao_ativo']) {

            $loginAtivo = TRUE;
        }

        $dadosView = [
            'list' => $list,
            'controller' => $this->params('controller'),
            'atributos' => [],
            'configList' => $this->getConfigList(),
            'liberaPagamentoMensalidade' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->liberaPagamentoMensalidade($auth),
            'pagamento' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->listPagamento($auth),
            'loginAtivo' => $loginAtivo,
            'auth' => $auth,
        ];

        $view = new ViewModel($dadosView);
        return $view->setTerminal(true);
    }

    /**
     * 
     * @return type
     */
    public function indexAction() {
        return parent::index($this->service, $this->form);
    }

    /**
     * 
     * @return type
     */
    public function cadastroAction() {
        return parent::cadastro($this->service, $this->form);
    }

    /**
     * 
     * @return type
     */
    public function excluirAction() {
        return parent::excluir($this->service, $this->form);
    }

    /**
     * 
     * @return type
     */
    public function viewVideoAction() {

        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();
        $mensalidadeFoiPaga = $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->mensalidadeFoiPaga($auth);

        if ($mensalidadeFoiPaga) {

            $view = new ViewModel([
                'cod' => $this->getRequest()->getPost()->get('cod'),
            ]);
            return $view->setTerminal(true);
        } else {
            $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->liberaPagamentoMensalidade($auth);

            $cursosService = new \McNetwork\Service\CursosService();
            $cursosService->setIdSituacao($this->getConfigList()['situacao_ativo']);
            $list = $cursosService->filtrarObjeto();

            $loginService = new \Login\Service\LoginService();
            $loginService->setIdUsuario($auth->id_usuario);
            $loginEntity = $loginService->filtrarObjeto()->current();

            $loginAtivo = FALSE;
            if ($loginEntity->getIdSituacao() == $this->getConfigList()['situacao_ativo']) {

                $loginAtivo = TRUE;
            }

            $dadosView = [
                'list' => $list,
                'controller' => $this->params('controller'),
                'atributos' => [],
                'configList' => $this->getConfigList(),
                'liberaPagamentoMensalidade' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->liberaPagamentoMensalidade($auth),
                'pagamento' => $this->getServiceLocator()->get('Pagamento/Service/PagamentoService')->listPagamento($auth),
                'loginAtivo' => $loginAtivo,
                'auth' => $auth,
            ];

            $view = new ViewModel($dadosView);
            $view->setTemplate('mc-network/cursos/course-information.phtml'); // path to phtml file under view folder
            return $view->setTerminal(true);
        }
    }

    /**
     * 
     * @return boolean
     */
    public function gravarAction() {


        $auth = $this->getServiceLocator()->get('AuthService')->getStorage()->read();

        $suporteAsContratoService = new \SuporteAsContrato\Service\SuporteAsContratoService();
        $suporteAsContratoService->setIdContrato($auth->id_contrato);
        $suporteAsContratoService->excluir();      
        
        $empresas = $this->getRequest()->getPost()->get('id_empresa');
        
        if (!empty($empresas)) {
            foreach ($empresas as $empresa) {

                $suporteAsContratoService = new \SuporteAsContrato\Service\SuporteAsContratoService();
                $suporteAsContratoService->exchangeArray([
                    'id_suporte_as_contrato' => NULL,
                    'id_empresa' => \Estrutura\Helpers\Cript::dec($empresa),
                    'id_contrato' => $auth->id_contrato,
                    'id_situacao' => $this->getConfigList()['situacao_ativo'],
                ]);
                $suporteAsContratoService->salvar();
            }
        }
        
        return new JsonModel([
            'status' => 'success'
        ]);
        
        
    }

}
