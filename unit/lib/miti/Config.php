<?php
class Config{
	public function __construct(){
		$this->diretorios();
		$this->banco();
		$this->autoload();
	}
	
	private function diretorios(){
		if(!defined('RAIZ')){define('RAIZ','/var/www/miti_modelo/');}
	}
	
	private function banco(){
		if(!defined('BD_SERVIDOR')){define('BD_SERVIDOR','localhost');}
		if(!defined('BD_USUARIO')){define('BD_USUARIO','root');}
		if(!defined('BD_SENHA')){define('BD_SENHA','root');}
		if(!defined('BD_BANCO')){define('BD_BANCO','miti_unit');}
		if(!defined('BD_CHARSET')){define('BD_CHARSET','latin1');}
	}
	
	private function autoload(){
		if(!function_exists('miti_autoload')){
			function miti_autoload($classe){
				if(file_exists(RAIZ.'lib/miti/'.$classe.'.php')){
					require RAIZ.'lib/miti/'.$classe.'.php';
				}
			}
			spl_autoload_register('miti_autoload');
		}
	}
}