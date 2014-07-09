<?php
class Config{
	public function __construct($Classe,$restrito,$sessao='login'){
		$this
			->ambiente()
			->sistema()
			->banco()
			->erro()
			->timezone()
			->charset()
			->raiz()
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
			define('SISTEMA','Miti API');
		}else{
			define('SISTEMA','Miti API 1.1.8');
		}
		
		return $this;
	}
	
	private function banco(){
		if(AMBIENTE===0){
			define('BD_SERVIDOR','servidor');
			define('BD_USUARIO','usuario');
			define('BD_SENHA','senha');
			define('BD_BANCO','banco');
			define('BD_CHARSET','charset');
		}else if(AMBIENTE===1){
			define('BD_SERVIDOR','servidor');
			define('BD_USUARIO','usuario');
			define('BD_SENHA','senha');
			define('BD_BANCO','banco');
			define('BD_CHARSET','charset');
		}
		
		return $this;
	}
	
	private function erro(){
		error_reporting(-1);
		
		if(AMBIENTE===0){
			ini_set('display_errors',0);
		}else{
			ini_set('display_errors',1);
		}
		
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
			define('RAIZ',$_SERVER['DOCUMENT_ROOT'].'/');
		}else if(AMBIENTE===1){
			define('RAIZ',$_SERVER['DOCUMENT_ROOT'].'/MitiAPI/');
		}
		
		return $this;
	}
	
	private function sessao($restrito,$sessao){
		session_start();
		
		if($restrito&&!isset($_SESSION[$sessao])){
			$_SESSION['status']='Você não está autenticado.';
			header('location:'.RAIZ.'admin/index.php');
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
		function mitiAutoload($classe){
			$pacotes=array('adt','miti');
			
			foreach($pacotes as $v){
				if(file_exists(RAIZ.$v.'/'.$classe.'.php')){
					require RAIZ.$v.'/'.$classe.'.php';
					break;
				}
			}
		}
		
		spl_autoload_register('mitiAutoload');
		
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
