<?php

namespace Application\Controller;

use Estrutura\Controller\AbstractEstruturaController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractEstruturaController {

    public function indexAction() {

        return $this->redirect()->toRoute('navegacao', ['controller' => 'admin-index', 'action' => 'index']);
    }

    public function infoAction() {

        phpinfo();
        exit();
    }

    public function politicaPrivacidadeAction() {

        return new ViewModel([]);
    }

    public function termoUtilizacaoAction() {

        return new ViewModel([]);
    }

    public function declaracaoSegurancaAction() {
        return new ViewModel([]);
    }
    
    public function contratoAction() {
        return new ViewModel([]);
    }
    
    public function contratoPdfAction() {
        
        $fileData = file_get_contents(BASE_PATCH . '/data/arquivos/contrato/contrato-venda-produtos.pdf');
        header('Content-type:application/pdf');
        echo $fileData;
        exit;
    }

}
