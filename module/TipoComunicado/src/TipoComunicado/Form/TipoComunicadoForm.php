<?php

namespace TipoComunicado\Form;

use Estrutura\Form\AbstractForm;
use Estrutura\Form\FormObject;
use Zend\InputFilter\InputFilter;

class TipoComunicadoForm extends AbstractForm{
    public function __construct($options=[]){
        parent::__construct('tipocomunicadoform');

        $this->inputFilter = new InputFilter();
        $objForm = new FormObject('tipocomunicadoform',$this,$this->inputFilter);
        $objForm->hidden("id")->required(false)->label("Id");  
        $objForm->text("nm_tipo_comunicado")->required(false)->label("Tipo Comunicado");  

        $this->formObject = $objForm;
    }

    public function getInputFilter()
    {
        return $this->inputFilter;
    }
}