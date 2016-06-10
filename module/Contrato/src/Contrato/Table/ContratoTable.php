<?php

namespace Contrato\Table;

use Estrutura\Table\AbstractEstruturaTable;

class ContratoTable extends AbstractEstruturaTable{

    public $table = 'contrato';
    public $campos = [
        'id_contrato'=>'id', 
        'dt_adesao'=>'dt_adesao', 
        'vl_saldo'=>'vl_saldo', 
        'id_situacao'=>'id_situacao', 
        'id_usuario'=>'id_usuario', 
        'id_contrato_origem'=>'id_contrato_origem', 
        'id_contrato_indicacao'=>'id_contrato_indicacao', 

    ];

}