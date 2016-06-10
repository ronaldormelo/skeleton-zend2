<?php

namespace Gerador\Controller;

use Estrutura\Controller\AbstractEstruturaController;
use Gerador\Service\Gerador;
use Gerador\Service\GeradorColuna;
use Gerador\Service\GeradorTabela;

class GeradorController extends AbstractEstruturaController {

    protected function playAction() {

        $id = $this->params('id');

        $service = new GeradorTabela();
        $tabelas = $service->filtrarObjeto();

        $colunas = new GeradorColuna();
        $mapa = [];
        foreach ($tabelas as $tabela) {

            if ($id) {
                if ($id === $tabela->getTableName()) {
                    
                    $colunas->setTableSchema($tabela->getTableSchema());
                    $colunas->setTableName($tabela->getTableName());                    
                    $mapa[$tabela->getTableName()] = $colunas->filtrarObjeto();
                }
            } else {
                $colunas->setTableName($tabela->getTableName());
                $colunas->setTableName($tabela->getTableName());                    
                $mapa[$tabela->getTableName()] = $colunas->filtrarObjeto();
            }
        }
        
        $gerador = new Gerador($mapa);
        $gerador->gerar();

        $this->addSuccessMessage('Gerado com sucesso');

        return $this->redirect()->toRoute('gerador-home');
    }

}
