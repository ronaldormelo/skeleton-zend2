<?php

namespace Empresa\Form;

use Estrutura\Form\AbstractForm;
use Estrutura\Form\FormObject;
use Zend\InputFilter\InputFilter;

class EmpresaForm extends AbstractForm{
    
    public function __construct($options=[]){
        
        parent::__construct('empresaform');

        $this->inputFilter = new InputFilter();
        $objForm = new FormObject('empresaform',$this,$this->inputFilter);
        $objForm->hidden("id")->required(false)->label("Id");
        $objForm->text("nm_empresa")->required(false)->label("Empresa");  
        $objForm->text("ar_logo")->required(false)->label("Logo");  
        $objForm->combo("id_situacao", '\Situacao\Service\SituacaoService', 'id', 'nm_situacao')->required(true)->label("Situação");  

        $this->formObject = $objForm;
    }

    public function getInputFilter()
    {
        return $this->inputFilter;
    }
}