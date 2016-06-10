<?php

namespace TipoComunicado\Table;

use Estrutura\Table\AbstractEstruturaTable;

class TipoComunicadoTable extends AbstractEstruturaTable{

    public $table = 'tipo_comunicado';
    public $campos = [
        'id_tipo_comunicado'=>'id', 
        'nm_tipo_comunicado'=>'nm_tipo_comunicado', 

    ];

}