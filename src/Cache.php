<?php
namespace Miti;

class Cache{
	private static function verificarExistenciaDaExtensao(){
		if(!extension_loaded('apache2handler')){
			throw new \RuntimeException('A classe '.__CLASS__.' depende da extensão apache2handler.');
		}
	}
	
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
