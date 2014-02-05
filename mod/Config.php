<?php
class Config{
	public function __construct($restrito,$dir='',$sessao='login'){
		//erros
		error_reporting(E_ALL);
		//ini_set('display_errors',0);
		
		//sistema
		define('SISTEMA','Miti Modelo 4.2.69');
		
		//diretorios
		define('RAIZ','http://'.$_SERVER['HTTP_HOST'].'/'.'miti_modelo/');
		define('DIR',$dir);
		
		//banco (localhost:root::miti_modelo:latin1)
		define('BD_SERVIDOR','localhost');
		define('BD_USUARIO','root');
		define('BD_SENHA','');
		define('BD_BANCO','miti_modelo');
		define('BD_CHARSET','latin1');
		
		//sessao
		session_start();
		
		if($restrito==true&&isset($_SESSION[$sessao])==false){
			$_SESSION['status']='Você não está autenticado';
			header('location:'.RAIZ.'login.php'); exit();
		}
		
		//autoload
		function miti_autoload($classe){
			$pacotes=array('mod','lib/miti');
			
			foreach($pacotes as $v){
				if(file_exists(DIR.$v.'/'.$classe.'.php')==true){
					require(DIR.$v.'/'.$classe.'.php');
					break;
				}
			}
		}
		spl_autoload_register('miti_autoload');
		
		//procedimentos
		require_once(DIR.'proc.php');
	}
}
?>
