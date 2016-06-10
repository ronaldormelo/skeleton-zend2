<?php

namespace McNetwork\Form;

use Estrutura\Form\AbstractForm;
use Estrutura\Form\FormObject;
use Zend\InputFilter\InputFilter;

class SuporteForm extends AbstractForm {

    public function __construct($options = []) {

        parent::__construct('suporteform');

        $this->inputFilter = new InputFilter();
        $objForm = new FormObject(
                'suporteform', $this, $this->inputFilter
        );

        $empresas[] = [
            'value' => 1,
            'id' => 'id_empresa1',
            'label' => 'Sim',
            'selected' => true,
        ];
        $empresas[] = [
            'value' => 2,
            'id' => 'id_empresa2',
            'label' => 'Não',
            'selected' => false,
        ];
        $objForm->checkbox('id_empresa', $empresas)->required(true)->label('Gostaria de receber suporte de mais empresas?');

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
