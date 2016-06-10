<?php

namespace Estado\Controller;

use Estrutura\Controller\AbstractCrudController;

class EstadoController extends AbstractCrudController
{
    /**
     * @var \Estado\Service\Estado
     */
    protected $service;

    /**
     * @var \Estado\Form\Estado
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
