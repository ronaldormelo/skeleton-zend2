<?php
namespace McNetwork\Entity;

use Estrutura\Service\AbstractEstruturaService;

class CursosEntity extends AbstractEstruturaService{
    
    protected $id;
    protected $id_tipo;
    protected $nm_curso;
    protected $tx_descricao;
    protected $nr_unidade;
    protected $ar_video;
    protected $ar_imagem;
    protected $id_situacao;    
} 