<?php

namespace Usuario\Controller;

use Estrutura\Controller\AbstractCrudController;
use Zend\View\Model\ViewModel;

class UsuarioController extends AbstractCrudController
{
    /**
     * @var \Usuario\Service\Usuario
     */
    protected $service;

    /**
     * @var \Usuario\Form\Usuario
     */
    protected $form;

    public function __construct(){
        parent::init();
    }

    public function indexAction()
    {
        
        return new ViewModel([
            'service' => $this->service,
            'form' => $this->form,
            'controller' => $this->params('controller'),
            'atributos' => array()
        ]);        
    }
    
    public function indexPaginationAction()
    {
        
        $filter = $this->getFilterPage();

        $camposFilter = [
'0' => [
    'filter' => "LOWER(nm_usuario) LIKE ? ",
    'mascara' => 'strtolower($value)',
],
'1' => [
    'filter' => "DATE_FORMAT(dt_nascimento,'%d/%m/%Y') LIKE ? "
],
'2' => [
    'filter' => "LOWER(nu_rg) LIKE ? ",
    'mascara' => 'strtolower($value)',
],
'3' => [
    'filter' => "nu_cpf LIKE ? ",
    'mascara' => '\Estrutura\Helpers\Cpf::cpfFilter($value)',
],
'4' => [
    'filter' => "LOWER(nm_profissao) LIKE ? ",
    'mascara' => 'strtolower($value)',
],
'5' => [
    'filter' => "LOWER(nm_nacionalidade) LIKE ? ",
    'mascara' => 'strtolower($value)',
],
    '6' => NULL,
];
        
        $paginator = $this->service->getPaginatorUsuario($filter, $camposFilter);

        $paginator->setItemCountPerPage($paginator->getTotalItemCount());

        $countPerPage = $this->getCountPerPage(
                current(\Estrutura\Helpers\Pagination::getCountPerPage($paginator->getTotalItemCount()))
        );

        $paginator->setItemCountPerPage($this->getCountPerPage(
                        current(\Estrutura\Helpers\Pagination::getCountPerPage($paginator->getTotalItemCount()))
        ))->setCurrentPageNumber($this->getCurrentPage());

        $viewModel = new ViewModel([
            'service' => $this->service,
            'form' => $this->form,
            'paginator' => $paginator,
            'filter' => $filter,
            'countPerPage' => $countPerPage,
            'camposFilter' => $camposFilter,
            'controller' => $this->params('controller'),
            'atributos' => array()
        ]);

        return $viewModel->setTerminal(TRUE);        
    }

    public function gravarAction(){
        
        if ($result = parent::gravar($this->getServiceObj(), $this->getFormObj())) {
            
            $this->addSuccessMessage('Salvo com sucesso');
            $this->redirect()->toRoute('navegacao', array(
                'controller' => $this->params('controller'), 
                'action' => 'index')
            );
        }
        return $result;
    }

    public function cadastroAction()
    {
        return parent::cadastro($this->getServiceObj(), $this->getFormObj());
    }
    
    public function editaAction()
    {
        return parent::cadastro($this->getServiceObj(), $this->getFormObj());
    }

    public function excluirAction()
    {
        return parent::excluir($this->getServiceObj(), $this->getFormObj());
    }
}
