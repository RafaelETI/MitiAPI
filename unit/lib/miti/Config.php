<?php
class Config{
	private static $instance=false;
	
	private function __construct(){
		$this->diretorios();
		$this->banco();
		$this->autoload();
	}
	
	public static function setInstance(){
		if(!self::$instance){
			self::$instance=true;
			new Config;
		}
	}
	
	private function diretorios(){
		define('RAIZ','/var/www/miti_modelo/');
	}
	
	private function banco(){
		define('BD_SERVIDOR','localhost');
		define('BD_USUARIO','root');
		define('BD_SENHA','root');
		define('BD_BANCO','miti_unit');
		define('BD_CHARSET','latin1');
	}
	
	private function autoload(){
		function miti_autoload($classe){
			if(file_exists(RAIZ.'lib/miti/'.$classe.'.php')){
				require RAIZ.'lib/miti/'.$classe.'.php';
			}
		}
		
		spl_autoload_register('miti_autoload');
	}
}