<?php

namespace SituacaoEmpresaContrato\Table;

use Estrutura\Table\AbstractEstruturaTable;

class SituacaoEmpresaContratoTable extends AbstractEstruturaTable{

    public $table = 'situacao';
    public $campos = [
        'id_situacao_empresa_contrato'=>'id', 
        'nm_situacao_empresa_contrato'=>'nm_situacao_empresa_contrato', 

    ];

}