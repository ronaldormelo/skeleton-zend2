<?php

namespace Estrutura\Helpers;

/**
 *
 * @author anonymous
 *
 */
class Url {

    /**
     *
     * @param string $cep
     * @return string $cep
     * Ex:
     * $cep = 71.123-124
     * $cep = Modulo_Helpers_Cep::cepFilter($cep); // retorna 71123124
     */
    public static function urlFilter($url) {


        // in case scheme relative URI is passed, e.g., //www.google.com/
        $url = trim($url, '/');

        // If scheme not included, prepend it
        if (!preg_match('#^http(s)?://#', $url)) {
            $url = 'http://' . $url;
        }

        $urlParts = parse_url($url);

        // remove www
        return preg_replace('/^www\./', '', $urlParts['host']);
    }

}
