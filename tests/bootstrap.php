<?php
mb_internal_encoding('UTF-8');

spl_autoload_register(function($Classe){
	$Classe = str_replace('\\', '/', $Classe);
	$Classe = str_replace('Miti', 'src', $Classe);
	$arquivo = __DIR__."/../$Classe.php";
	if(file_exists($arquivo)){require_once $arquivo;}
});
