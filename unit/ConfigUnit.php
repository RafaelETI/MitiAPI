<?php
new ConfigUnit;

class ConfigUnit{
	public function __construct(){
		$this
			->ambiente()
			->erro()
			->timezone()
			->charset()
			->raiz()
			->banco()
			->sessao()
			->autoload()
		;
	}
	
	private function ambiente(){
		define('AMBIENTE',1);
		return $this;
	}
	
	private function erro(){
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		return $this;
	}
	
	private function timezone(){
		date_default_timezone_set('America/Sao_Paulo');
		return $this;
	}
	
	private function charset(){
		header('Content-Type: text/html; charset=iso-8859-1');
		return $this;
	}
	
	private function raiz(){
		if(AMBIENTE===0){
			define('RAIZ','c:/apache24/htdocs/miti_modelo/');
		}else if(AMBIENTE===1){
			define('RAIZ','/var/www/html/miti_modelo/');
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
	
	private function sessao(){
		session_start();
		return $this;
	}
	
	private function autoload(){
		function miti_autoload($classe){
			$pacotes=array('adt','lib/miti');
			
			foreach($pacotes as $v){
				if(file_exists(RAIZ.$v.'/'.$classe.'.php')){
					require RAIZ.$v.'/'.$classe.'.php';
					break;
				}
			}
		}
		
		spl_autoload_register('miti_autoload');
		
		return $this;
	}
}
