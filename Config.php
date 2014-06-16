<?php
class Config{
	public function __construct($Classe,$restrito,$raiz='',$sessao='login'){
		$this
			->ambiente()
			->sistema()
			->banco()
			->erro()
			->timezone()
			->charset()
			->raiz($raiz)
			->sessao($restrito,$sessao)
			->autoload()
			->requisicao($Classe)
		;
	}
	
	private function ambiente(){
		define('AMBIENTE',1);
		return $this;
	}
	
	private function sistema(){
		if(AMBIENTE===0){
			define('SISTEMA','MitiAPI');
		}else if(AMBIENTE===1){
			define('SISTEMA','MitiAPI 1.0.0');
		}
		
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
	
	private function erro(){
		error_reporting(E_ALL|E_STRICT);
		ini_set('display_errors',AMBIENTE);
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
	
	private function raiz($raiz){
		define('RAIZ',$raiz);
		return $this;
	}
	
	private function sessao($restrito,$sessao){
		session_start();
		
		if($restrito&&!isset($_SESSION[$sessao])){
			$_SESSION['status']='Você não está autenticado.';
			header('location:'.RAIZ.'admin/login.php');
			exit;
		}
		
		return $this;
	}
	
	public static function verificarSessao($sessao='login'){
		if(!isset($_SESSION[$sessao])){
			throw new Exception('Você não tem permissão.');
		}
	}
	
	private function autoload(){
		function miti_autoload($classe){
			$pacotes=array('adt','miti');
			
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
	
	private function requisicao($Classe){
		if(isset($_REQUEST['metodo'])){
			$this->tratarRequisicao();
			
			try{
				$Objeto=new $Classe;
				$Objeto->$_REQUEST['metodo']();
				header('location:'.$_REQUEST['url']);
				exit;
			}catch(Exception $e){
				$_SESSION['status']=$e->getMessage();
			}
		}
		
		return $this;
	}
	
	private function tratarRequisicao(){
		unset($_POST['metodo']);
		unset($_POST['url']);
		unset($_GET['metodo']);
		unset($_GET['url']);
		
		if(!isset($_REQUEST['url'])){
			$_REQUEST['url']=$_SERVER['REQUEST_URI'];
		}
	}
}
