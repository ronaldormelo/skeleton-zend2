<?php

namespace ComunicadoAsContrato\Form;

use Estrutura\Form\AbstractForm;
use Estrutura\Form\FormObject;
use Zend\InputFilter\InputFilter;

class ComunicadoAsContratoForm extends AbstractForm{
    public function __construct($options=[]){
        parent::__construct('comunicadoascontratoform');

        $this->inputFilter = new InputFilter();
        $objForm = new FormObject('comunicadoascontratoform',$this,$this->inputFilter);
        $objForm->combo("id_comunicado", '\Comunicado\Service\ComunicadoService', 'id', 'nm_comunicado')->required(true)->label("id comunicado");  
        $objForm->combo("id_contrato", '\Contrato\Service\ContratoService', 'id', 'nm_contrato')->required(true)->label("id contrato");  

        $this->formObject = $objForm;
    }

    public function getInputFilter()
    {
        return $this->inputFilter;
    }
}