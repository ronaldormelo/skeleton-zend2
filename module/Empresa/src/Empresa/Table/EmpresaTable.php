<?php

namespace Empresa\Table;

use Estrutura\Table\AbstractEstruturaTable;

class EmpresaTable extends AbstractEstruturaTable {

    public $table = 'empresa';
    public $campos = [
        'id_empresa' => 'id',
        'nm_empresa' => 'nm_empresa',
        'ar_logo' => 'ar_logo',
        'id_situacao' => 'id_situacao',
        'ar_video' => 'ar_video',
        'tx_slogan' => 'tx_slogan',
        'tx_descricao' => 'tx_descricao',
        'ur_link' => 'ur_link',
        'bl_tem_codigo' => 'bl_tem_codigo',
        'ur_dominio' => 'ur_dominio',
        'ar_img_video' => 'ar_img_video',
    ];

}
