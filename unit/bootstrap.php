<?php
//erros
error_reporting(E_ALL);
ini_set('display_errors',1);

//diretorios
define('RAIZ','/var/www/miti_modelo/');

//banco
define('BD_SERVIDOR','localhost');
define('BD_USUARIO','root');
define('BD_SENHA','root');
define('BD_BANCO','miti_unit');
define('BD_CHARSET','latin1');

//autoload
function miti_autoload($classe){
	if(file_exists(RAIZ.'lib/miti/'.$classe.'.php')){
		require RAIZ.'lib/miti/'.$classe.'.php';
	}
}
spl_autoload_register('miti_autoload');