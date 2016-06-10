<?php

namespace McNetwork\Form;

use Estrutura\Form\AbstractForm;
use Estrutura\Form\FormObject;
use Zend\InputFilter\InputFilter;

class DadosPessoaisForm extends AbstractForm {

    public function __construct($options = []) {

        parent::__construct('usuarioform');

        $this->inputFilter = new InputFilter();
        $objForm = new FormObject(
                'usuarioform', $this, $this->inputFilter
        );

        $objForm->hidden("id")->required(false)->label("Id");
        $objForm->text('nm_usuario')->required(true)->label('Nome completo');
        $objForm->cpf("nu_cpf")->required(true)->label("CPF");
        $objForm->email("em_email")->required(false)->label("E-mail");

        $this->formObject = $objForm;
    }

    /**
     * 
     * @return type
     */
    public function getInputFilter() {
        return $this->inputFilter;
    }

}
