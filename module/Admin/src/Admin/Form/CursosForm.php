<?php

namespace McNetwork\Form;

use Estrutura\Form\AbstractForm;
use Estrutura\Form\FormObject;
use Zend\InputFilter\InputFilter;

class CursosForm extends AbstractForm {

    public function __construct($options = []) {

        parent::__construct('cursos');

        $this->inputFilter = new InputFilter();
        $objForm = new FormObject('cursos', $this, $this->inputFilter);
        $objForm->hidden('id_curso')->required(false);
        $objForm->text('nm_curso')->required(true)->label('Nome');
        $objForm->text('nr_unidade')->required(true)->label('Unidade');
        $objForm->textarea('tx_descricao')->required(true)->label('Descrição');
        $objForm->file('ar_video')->required(true)->label('Video');
        $objForm->file('ar_imagem')->required(true)->label('Imagem');
        $objForm->checkbox('id_situacao')->required(true)->label('Situação');
        $this->formObject = $objForm;
    }

    public function getInputFilter() {
        return $this->inputFilter;
    }

}
