<?php

namespace McNetwork\Form;

use Estrutura\Form\AbstractForm;
use Estrutura\Form\FormObject;
use Zend\InputFilter\InputFilter;

class RedeForm extends AbstractForm
{

    public function __construct($options = [])
    {

        parent::__construct('rede');

        $this->inputFilter = new InputFilter();
        $objForm = new FormObject('rede', $this, $this->inputFilter);        
        $this->formObject = $objForm;
    }

    public function getInputFilter()
    {
        return $this->inputFilter;
    }

}
