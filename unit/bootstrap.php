<?php
new Config;

class Config{
	public function __construct(){
		$this
			->ambiente()
			->charset()
			->erro()
			->raiz()
			->banco()
			->autoload()
		;
	}
	
	private function ambiente(){
		define('AMBIENTE',1);
		return $this;
	}
	
	private function charset(){
		header('Content-Type: text/html; charset=iso-8859-1');
		return $this;
	}
	
	private function erro(){
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		
		return $this;
	}
	
	private function raiz(){
		if(AMBIENTE===0){
			define('RAIZ','c:/apache24/htdocs/miti_modelo/');
		}else if(AMBIENTE===1){
			define('RAIZ','/var/www/miti_modelo/');
		}
		
		return $this;
	}
	
	private function banco(){
		if(AMBIENTE===0){
			define('BD_SERVIDOR','localhost');
			define('BD_USUARIO','root');
			define('BD_SENHA','root');
			define('BD_BANCO','miti_unit');
			define('BD_CHARSET','latin1');
		}else if(AMBIENTE===1){
			define('BD_SERVIDOR','localhost');
			define('BD_USUARIO','root');
			define('BD_SENHA','root');
			define('BD_BANCO','miti_unit');
			define('BD_CHARSET','latin1');
		}
		
		return $this;
	}
	
	private function autoload(){
		function miti_autoload($classe){
			//precisa de verificar se existe por causa do phpunit
			if(file_exists(RAIZ.'lib/miti/'.$classe.'.php')){
				require RAIZ.'lib/miti/'.$classe.'.php';
			}
		}
		
		spl_autoload_register('miti_autoload');
		
		return $this;
	}
}
