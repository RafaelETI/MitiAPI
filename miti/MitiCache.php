<?php
class MitiCache{
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
