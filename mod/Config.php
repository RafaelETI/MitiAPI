<?php
class Config{
	public function __construct($restrito,$dir='',$sessao='login'){
		//erros
		error_reporting(E_ALL);
		//ini_set('display_errors',0);
		
		//raiz
		define('RAIZ','http://'.$_SERVER['HTTP_HOST'].'/'.'miti_modelo/');
		
		//sessao
		session_start();
		
		if($restrito==true&&isset($_SESSION[$sessao])==false){
			$_SESSION['status']='Você não está autenticado';
			header('location:'.RAIZ); exit();
		}
		
		//autoload
		define('DIR',$dir);
		
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
		
		//constantes
		define('SISTEMA','Miti Modelo 3.2.69');
		
		define('BD_SERVIDOR','localhost');
		define('BD_USUARIO','root');
		define('BD_SENHA','');
		define('BD_BANCO','miti_modelo');
		define('BD_CHARSET','latin1');
		//define('BD_SERVIDOR','localhost');
		//define('BD_USUARIO','root');
		//define('BD_SENHA','');
		//define('BD_BANCO','miti_modelo');
		//define('BD_CHARSET','latin1');
		
		//procedimentos
		require_once($dir.'proc.php');
	}
}
?>
