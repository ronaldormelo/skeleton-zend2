<?php

namespace Estrutura\Service;

class HtmlHelper {

    public static function botaoLink($url, $icone, $attributos = [], $tipoBtn = 'btn-primary') {

        $botao = '<a href="' . $url . '" ' . self::arrayToString($attributos) . '>' .
                '<button type="button" class="btn ' . $tipoBtn . '">' .
                '<span class="' . $icone . '"></span>' .
                '</button>' .
                '</a>';

        return $botao;
    }

    public static function botao($id, $icone, $attributos = [], $tipoBtn = 'btn-primary') {

        $botao = '<span ' . self::arrayToString($attributos) . ' rel="' . $id . '">' .
                '<button type="button"  class="btn ' . $tipoBtn . '">' .
                '<span class="' . $icone . '"></span>' .
                '</button>' .
                '</span>';

        return $botao;
    }

    public static function botaoExcluirConfirm($id) {
        $attributos = [
            'class' => 'btn-excluir btn-sm',
            'title' => 'Excluir'
        ];
        return self::botao($id, 'glyphicon glyphicon-remove-sign', $attributos, 'btn-danger');
    }

    public static function botaoExcluir($url) {
        $attributos = [
            'class' => 'btn-excluir btn-sm',
            'title' => 'Excluir'
        ];
        return self::botaoLink($url, 'glyphicon glyphicon-remove-sign', $attributos, 'btn-danger');
    }

    public static function botaoAlterar($url) {
        $attributos = [
            'class' => 'btn-alterar btn-sm',
            'title' => 'Alterar'
        ];
        return self::botaoLink($url, 'glyphicon glyphicon-edit', $attributos);
    }

    private static function arrayToString($array) {
        $string = '';
        foreach ($array as $chave => $item) {
            $string .= "{$chave}='{$item}' ";
        }
        return $string;
    }

}
