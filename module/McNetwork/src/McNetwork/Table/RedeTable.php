<?php

namespace McNetwork\Table;

use Estrutura\Table\AbstractEstruturaTable;

class CursosTable extends AbstractEstruturaTable{

    public $table = 'curso';
    public $campos = [
        'id_curso' => 'id',
    ];
}