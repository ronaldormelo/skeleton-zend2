<?php

namespace Estrutura\Helpers;

/**
 *
 * @author anonymous
 *
 */
class ArrayHelper {

    /**
     * Converte array multidimencional $_FILE por atributos (tmp_name, size...)
     * em um array multidimencional por arquivo
     * @param unknown_type $files
     * @return array
     */
    public static function unidim($arr) {
        global $junto;
        foreach ($arr as $v) {
            if (!is_array($v)) {
                $junto[] = $v;
            } else {
                self::unidim($v);
            }
        }
        return $junto;
    }

}
