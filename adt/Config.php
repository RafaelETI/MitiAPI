<?php
class Config{
	public function __construct($restrito,$dir='',$sessao='login'){
		$this->erros();
		$this->sistema();
		$this->raiz($dir);
		$this->banco();
		$this->sessao($restrito,$sessao);
		$this->autoload();
		$this->procedimentos();
	}
	
	private function erros(){
		error_reporting(E_ALL);
		ini_set('display_errors',1);
	}
	
	private function sistema(){
		define('SISTEMA','Miti Modelo 4.14.89');
	}
	
	private function raiz($dir){
		define('RAIZ',$dir);
	}
	
	private function banco(){
		//localhost:root:root:miti_modelo:latin1
		define('BD_SERVIDOR','localhost');
		define('BD_USUARIO','usuario');
		define('BD_SENHA','senha');
		define('BD_BANCO','banco');
		define('BD_CHARSET','latin1');
	}
	
	private function sessao($restrito,$sessao){
		session_start();
		
		if($restrito&&!isset($_SESSION[$sessao])){
			$_SESSION['login_erro']='Você não está autenticado';
			header('location:'.RAIZ.'main/login.php');
			exit;
		}
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
	}
	
	private function procedimentos(){
		require_once RAIZ.'main/proc.php';
	}
}