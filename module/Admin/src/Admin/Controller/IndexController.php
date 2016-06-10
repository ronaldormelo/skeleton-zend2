<?php

namespace Admin\Controller;

use Estrutura\Controller\AbstractEstruturaController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractEstruturaController {

    /**
     * 
     * @return ViewModel
     */
    public function indexAction() {

        return new ViewModel([]);
    }

    /**
     * 
     */
    public function infoAction() {

        phpinfo();
        die;
    }

}
