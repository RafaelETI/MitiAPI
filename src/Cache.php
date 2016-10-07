<?php
/**
 * Miti API, 2014 - 2015
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace Miti;

/**
 * Controle de cache
 * 
 * Não está testada pela dificuldade imposta pelo framework de teste, sendo que
 * ele não é executado via servidor web, fazendo com que não seja possível a
 * manipulação do cabeçalho HTTP.
 */
class Cache{
	/**
	 * Verifica a existência da extensão do PHP para trabalhar com o Apache
	 * 
	 * @throws \RuntimeException
	 */
	private static function verificarExistenciaDaExtensao(){
		if(!extension_loaded('apache2handler')){
			throw new \RuntimeException('A classe '.__CLASS__.' depende da extensão apache2handler.');
		}
	}
	
	/**
	 * Define o tempo de validade do recurso
	 * 
	 * Por recurso entende-se o conteúdo conseguido através de uma requisição à
	 * um URL.
	 * 
	 * Há uma dependência com o servidor Apache, porque a função getallheaders()
	 * é um alias da apache_request_headers().
	 * 
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
