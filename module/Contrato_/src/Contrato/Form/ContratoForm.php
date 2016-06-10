<?php

namespace Contrato\Form;

use Estrutura\Form\AbstractForm;
use Estrutura\Form\FormObject;
use Zend\InputFilter\InputFilter;

class ContratoForm extends AbstractForm{
    public function __construct($options=[]){
        parent::__construct('contratoform');

        $this->inputFilter = new InputFilter();
        $objForm = new FormObject('contratoform',$this,$this->inputFilter);
        $objForm->hidden("id")->required(false)->label("Id");  
        $objForm->text("dt_adesao")->required(false)->label("Data de adesão");  
        $objForm->text("vl_saldo")->required(false)->label("Saldo");  
        $objForm->combo("id_situacao", '\Situacao\Service\SituacaoService', 'id', 'nm_situacao')->required(true)->label("Situacao");  
        $objForm->combo("id_usuario", '\Usuario\Service\UsuarioService', 'id', 'nm_usuario')->required(true)->label("Usuario");  
        $objForm->hidden("id_contrato_origem")->required(true)->label("Id Contrato origem");  
        $objForm->hidden("id_contrato_indicacao")->required(true)->label("Id Contrato Indicacao");  

        $this->formObject = $objForm;
    }

    public function getInputFilter()
    {
        return $this->inputFilter;
    }
}