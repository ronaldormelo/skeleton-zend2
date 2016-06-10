<?php
namespace Estrutura\Helpers;
/**
 *
 * @author anonymous
 *
 */
class Cep {


	/**
	 *
	 * @param string $cep
	 * @return string $cep
	 * Ex:
	 * $cep = 71.123-124
	 * $cep = Modulo_Helpers_Cep::cepFilter($cep); // retorna 71123124
	 */
	public static function cepFilter( $cep ){

		return preg_replace('#[. -]#', '', $cep);
	}


	/**
	 *
	 * @param int $cep
	 * @return string $cep
	 * Ex:
	 * $cep = 71123124
	 * $cep = Modulo_Helpers_Cep::cepMask($cep); // retorna 71.123-124
	 */
	public static function cepMask( $cep ){

		if ($cep){

			return Utilities::mascaraformato('##.###-###', trim($cep));
		}else{

			return null;
		}
	}
}