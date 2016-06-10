<?php

namespace Contrato\Controller;

use Estrutura\Controller\AbstractCrudController;

class ContratoController extends AbstractCrudController
{
    /**
     * @var \Contrato\Service\Contrato
     */
    protected $service;

    /**
     * @var \Contrato\Form\Contrato
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
}
