<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Controle de cache
 * 
 * N�o est� testada pela dificuldade imposta pelo framework de teste, sendo que
 * ele n�o � executado via servidor web, fazendo com que n�o seja poss�vel a
 * manipula��o do cabe�alho HTTP.
 */
class Cache{
	/**
	 * Verifica a exist�ncia da extens�o do PHP para trabalhar com o Apache
	 * 
	 * @throws \RuntimeException
	 */
	private static function verificarExistenciaDaExtensao(){
		if(!extension_loaded('apache2handler')){
			throw new \RuntimeException('A classe '.__CLASS__.' depende da extens�o apache2handler.');
		}
	}
	
	/**
	 * Define o tempo de validade do recurso
	 * 
	 * Por recurso entende-se o conte�do conseguido atrav�s de uma requisi��o �
	 * um URL.
	 * 
	 * H� uma depend�ncia com o servidor Apache, porque a fun��o getallheaders()
	 * � um alias da apache_request_headers().
	 * 
	 * @api
	 * @param int $minutos
	 */
	public static function temporizar($minutos){
		self::verificarExistenciaDaExtensao();
		
		$segundos = $minutos * 60;
		
		header("Cache-Control: max-age=$segundos");

		$DateTime = new \DateTime;
		$agora = $DateTime->format(\DateTime::RFC1123);
		$validade = $DateTime->modify("$segundos sec")->format(\DateTime::RFC1123);
		
		header("Last-Modified: $validade");

		$header = getallheaders();
		
		if(isset($header['If-Modified-Since'])){
			if($header['If-Modified-Since'] > $agora){
				header("{$_SERVER["SERVER_PROTOCOL"]} 304 Not Modified");
				exit;
			}
		}
	}
}
