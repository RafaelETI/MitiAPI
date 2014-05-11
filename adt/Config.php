<?php
class Config{
	public function __construct($Classe,$restrito,$raiz='',$sessao='login'){
		$this
			->ambiente()
			->charset()
			->erro()
			->sistema()
			->raiz($raiz)
			->banco()
			->sessao($restrito,$sessao)
			->autoload()
			->objeto($Classe)
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
		ini_set('display_errors',AMBIENTE);
		
		return $this;
	}
	
	private function sistema(){
		define('SISTEMA','Miti Modelo 5.15.98');
		return $this;
	}
	
	private function raiz($raiz){
		define('RAIZ',$raiz);
		return $this;
	}
	
	private function banco(){
		if(AMBIENTE===0){
			define('BD_SERVIDOR','localhost');
			define('BD_USUARIO','usuario');
			define('BD_SENHA','senha');
			define('BD_BANCO','banco');
			define('BD_CHARSET','latin1');
		}else if(AMBIENTE===1){
			define('BD_SERVIDOR','localhost');
			define('BD_USUARIO','usuario');
			define('BD_SENHA','senha');
			define('BD_BANCO','banco');
			define('BD_CHARSET','latin1');
		}
		
		return $this;
	}
	
	private function sessao($restrito,$sessao){
		session_start();
		
		if($restrito&&!isset($_SESSION[$sessao])){
			$_SESSION['status']='Você não está autenticado';
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
	
	private function objeto($Classe){
		if(isset($_REQUEST['acao'])){
			try{
				$Objeto=new $Classe;
				$Objeto->$_REQUEST['acao']();
			}catch(Exception $e){
				$_SESSION['status']=$e->getMessage();
			}
		}
		
		return $this;
	}
}
