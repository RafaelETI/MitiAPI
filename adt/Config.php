<?php
class Config{
	public function __construct($restrito,$dir='',$sessao='login'){
		$this->erros();
		$this->sistema();
		$this->diretorios($dir);
		$this->banco();
		$this->sessao($restrito,$sessao);
		$this->autoload();
		$this->procedimentos();
	}
	
	private function erros(){
		error_reporting(E_ALL);
		//ini_set('display_errors',0);
	}
	
	private function sistema(){
		define('SISTEMA','Miti Modelo 4.11.89');
	}
	
	private function diretorios($dir){
		define('RAIZ','http://'.$_SERVER['HTTP_HOST'].'/'.'miti_modelo/');
		define('DIR',$dir);
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
			$_SESSION['status']='Voc� n�o est� autenticado';
			header('location:'.RAIZ.'login.php');
			exit;
		}
	}
	
	private function autoload(){
		function miti_autoload($classe){
			$pacotes=array('adt','lib/miti');
			
			foreach($pacotes as $v){
				if(file_exists(DIR.$v.'/'.$classe.'.php')){
					require DIR.$v.'/'.$classe.'.php';
					break;
				}
			}
		}
		
		spl_autoload_register('miti_autoload');
	}
	
	private function procedimentos(){
		require_once DIR.'main/proc.php';
	}
}