<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Controle de cache
 * 
 * Não está testada por causa da dificuldade imposta pelo framework de teste,
 * sendo que ele não é executado via servidor web, fazendo com que seja difícil
 * a manipulação do cabeçalho HTTP.
 */
class MitiCache{
	/**
	 * Define o tempo de validade do recurso
	 * 
	 * Por recurso entende-se o conteúdo conseguido através de uma requisição à
	 * um URL.
	 * 
	 * Pode ser que aja uma dependência com o servidor Apache, porque a função
	 * getallheaders() é um alias para a apache_request_headers().
	 * 
	 * @api
	 * @param int $minutos
	 */
	public static function definirTempo($minutos){
		$segundos=$minutos*60;
		
		header("cache-control:max-age=$segundos");

		$DateTime=new DateTime;
		$agora=$DateTime->format(DateTime::RFC1123);
		$DateTime->modify("+$segundos sec");
		$validade=$DateTime->format(DateTime::RFC1123);
		
		header("last-modified:$validade");

		$header=getallheaders();
		if(isset($header['If-Modified-Since'])){
			if($header['If-Modified-Since']>$agora){
				header($_SERVER["SERVER_PROTOCOL"].' 304 Not Modified');
				exit;
			}
		}
	}
}
