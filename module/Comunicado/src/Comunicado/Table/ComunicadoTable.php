<?php

namespace Comunicado\Table;

use Estrutura\Table\AbstractEstruturaTable;

class ComunicadoTable extends AbstractEstruturaTable{

    public $table = 'comunicado';
    public $campos = [
        'id_comunicado'=>'id', 
        'nm_titulo_comunicado'=>'nm_titulo_comunicado', 
        'tx_comunicado'=>'tx_comunicado', 
        'dt_comunicado'=>'dt_comunicado', 
        'dt_expiracao'=>'dt_expiracao', 
        'id_situacao'=>'id_situacao', 
        'id_tipo_comunicado'=>'id_tipo_comunicado', 

    ];

}