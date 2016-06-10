<?php

namespace SituacaoEmpresaContrato\Form;

use Estrutura\Form\AbstractForm;
use Estrutura\Form\FormObject;
use Zend\InputFilter\InputFilter;

class SituacaoEmpresaContratoForm extends AbstractForm {

    public function __construct($options = []) {
        parent::__construct('situacaoempresacontratoform');

        $this->inputFilter = new InputFilter();
        $objForm = new FormObject('situacaoempresacontratoform', $this, $this->inputFilter);
        $objForm->hidden("id")->required(false)->label("Id");
        $objForm->text("nm_situacao_empresa_contrato")->required(false)->label("Situação");

        $this->formObject = $objForm;
    }

    public function getInputFilter() {
        return $this->inputFilter;
    }

}
