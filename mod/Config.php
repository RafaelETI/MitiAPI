<?php
class Config{
	public function __construct($restrito){
		//erros
		//ini_set('display_errors',0);
		
		//sessao
		session_start();
		
		if($restrito==true&&isset($_SESSION['login'])==false){
			$_SESSION['status']='Você não está autenticado';
			header('location:geral.php?arquivo=login'); exit();
		}
		
		//autoload
		function miti_autoload($classe){
			$pacotes=array('mod','lib/miti');
			
			foreach($pacotes as $v){
				if(file_exists($v.'/'.$classe.'.php')==true){
					require($v.'/'.$classe.'.php');
					break;
				}
			}
		}
		spl_autoload_register('miti_autoload');

		//constantes
		define('SISTEMA','Miti Modelo 2.2.50');
		
		define('BD_SERVIDOR','localhost');
		define('BD_USUARIO','root');
		define('BD_SENHA','');
		define('BD_BANCO','miti_modelo');
		//define('BD_SERVIDOR','localhost');
		//define('BD_USUARIO','root');
		//define('BD_SENHA','');
		//define('BD_BANCO','miti_modelo');
	}
}
?>
