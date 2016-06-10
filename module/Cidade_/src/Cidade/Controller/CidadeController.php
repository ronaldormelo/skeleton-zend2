<?php

namespace Cidade\Controller;

use Estrutura\Controller\AbstractCrudController;
use Zend\View\Model\ViewModel;

class CidadeController extends AbstractCrudController
{
    /**
     * @var \Cidade\Service\Cidade
     */
    protected $service;

    /**
     * @var \Cidade\Form\Cidade
     */
    protected $form;

    public function __construct(){
        parent::init();
    }

    public function indexAction()
    {
        return parent::index($this->service, $this->form);
    }

    public function gravarAction(){
        return parent::gravar($this->service, $this->form);
    }

    public function cadastroAction()
    {
        return parent::cadastro($this->service, $this->form);
    }

    public function excluirAction()
    {
        return parent::excluir($this->service, $this->form);
    }
    
    public function obterCidadesAction()
    {
        
        $params = $this->getRequest()->getPost()->toArray();
        
        $form = new \Cidade\Form\CidadeEstadoForm(['params' => $params]);
        
        $dadosView = [
            'form' => $form,
            'controller' => $this->params('controller'),
        ];

        $view = new ViewModel($dadosView);
        return $view->setTerminal(true);
    }
}
