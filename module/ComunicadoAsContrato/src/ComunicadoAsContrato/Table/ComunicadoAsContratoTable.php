<?php

namespace ComunicadoAsContrato\Table;

use Estrutura\Table\AbstractEstruturaTable;

class ComunicadoAsContratoTable extends AbstractEstruturaTable{

    public $table = 'comunicado_as_contrato';
    public $campos = [
        'id_comunicado'=>'id_comunicado', 
        'id_contrato'=>'id_contrato', 

    ];

}