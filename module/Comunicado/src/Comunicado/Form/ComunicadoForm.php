<?php

namespace Comunicado\Form;

use Estrutura\Form\AbstractForm;
use Estrutura\Form\FormObject;
use Zend\InputFilter\InputFilter;

class ComunicadoForm extends AbstractForm {

    public function __construct($options = []) {
        parent::__construct('comunicadoform');

        $configList = $this->sm()
                ->get('Config\Service\ConfigService')
                ->getConfigList();

        $this->inputFilter = new InputFilter();
        $objForm = new FormObject('comunicadoform', $this, $this->inputFilter);
        $objForm->hidden("id")->required(false)->label("Id");
        $objForm->text("nm_titulo_comunicado")->required(true)->label("Título")->maxLength(30);
        $objForm->textareaHtml("tx_comunicado")->required(true)->label("Comunicado")->setAttribute('rows', '10');
        $objForm->dateTime("dt_comunicado")->required(true)->label("Data comunicado");
        $objForm->dateTime("dt_expiracao")->required(true)->label("Data expiração");
        $combo = $objForm->combo("id_situacao", '\Situacao\Service\SituacaoService', 'id', 'nm_situacao');
        $data = $combo->element()->getValueOptions();
        unset($data['']);
        $combo->element()->setValueOptions($data);
        $combo->required(true)->label("Situação");
        
        $objForm->radio("id_tipo_comunicado", [
            [
                'value' => $configList['id_tipo_comunicado_video'],
                'id' => 'id_perfil' . $configList['id_tipo_comunicado_video'],
                'label' => 'Vídeo',
                'selected' => true,
            ],
            [
                'value' => $configList['id_tipo_comunicado_texto'],
                'id' => 'id_perfil' . $configList['id_tipo_comunicado_texto'],
                'label' => 'Texto',
            ]
        ])->required(true)->label("Tipo de comunicado");

        $this->formObject = $objForm;
    }

    public function getInputFilter() {
        return $this->inputFilter;
    }

}
