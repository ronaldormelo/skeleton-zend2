<?php

namespace McNetwork\Table;

use Estrutura\Table\AbstractEstruturaTable;

class CursosTable extends AbstractEstruturaTable{

    public $table = 'curso';
    public $campos = [
        'id_curso' => 'id',
        'id_tipo' => 'id_tipo',
        'nm_curso' => 'nm_curso',
        'tx_descricao' => 'tx_descricao',
        'nr_unidade' => 'nr_unidade',
        'ar_video' => 'ar_video',
        'ar_imagem' => 'ar_imagem',
        'id_situacao' => 'id_situacao',        
    ];
}