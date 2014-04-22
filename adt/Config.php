<?php
class Config{
	public function __construct($classe,$restrito,$raiz='',$sessao='login'){
		$this
			->erros()
			->sistema()
			->raiz($raiz)
			->banco()
			->sessao($restrito,$sessao)
			->autoload()
			->objeto($classe);
	}
	
	private function erros(){
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		
		return $this;
	}
	
	private function sistema(){
		define('SISTEMA','Miti Modelo 5.14.92');
		return $this;
	}
	
	private function raiz($raiz){
		define('RAIZ',$raiz);
		return $this;
	}
	
	private function banco(){
		//localhost:root:root:miti_modelo:latin1
		define('BD_SERVIDOR','localhost');
		define('BD_USUARIO','usuario');
		define('BD_SENHA','senha');
		define('BD_BANCO','banco');
		define('BD_CHARSET','latin1');
		
		return $this;
	}
	
	private function sessao($restrito,$sessao){
		session_start();
		
		if($restrito&&!isset($_SESSION[$sessao])){
			$_SESSION['login_erro']='Você não está autenticado';
			header('location:'.RAIZ.'main/login.php');
			exit;
		}
		
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
	
	private function objeto($classe){
		if(isset($_REQUEST['acao'])){
			$Objeto=new $classe;
			$Objeto->$_REQUEST['acao']();
		}
		
		return $this;
	}
}