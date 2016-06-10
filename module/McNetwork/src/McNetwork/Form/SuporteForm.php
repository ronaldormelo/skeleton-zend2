<?php

namespace McNetwork\Form;

use Estrutura\Form\AbstractForm;
use Estrutura\Form\FormObject;
use Zend\InputFilter\InputFilter;

class SuporteForm extends AbstractForm
{

    public function __construct($options = [])
    {

        parent::__construct('suporteform');

        $this->inputFilter = new InputFilter();
        $objForm = new FormObject(
                'suporteform', $this, $this->inputFilter
        );        
        
        $listEmpresas = $this->sm()->get('Empresa/Service/EmpresaService')->fetchAllAtivo();
        $empresas = [];
        foreach ($listEmpresas as $key => $empresa) {
            $empresas[] = [
                'value' => \Estrutura\Helpers\Cript::enc($empresa->getId()),
                'id' => 'id_empresa' . $key,
                'label' => $empresa->getNmEmpresa(),
                'selected' => in_array(\Estrutura\Helpers\Cript::enc($empresa->getId()), $options['empresas']) ? true : false,
            ];
        }
        $objForm->checkbox('id_empresa', $empresas)->required(true)->label('Gostaria de receber suporte de mais empresas?');
        
        $this->formObject = $objForm;
    }

    /**
     * 
     * @return type
     */
    public function getInputFilter()
    {
        return $this->inputFilter;
    }

}
