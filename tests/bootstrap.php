<?php
ini_set('display_errors', 1);

$config['rest']['servidor'] = 'http://validate.jsontest.com';

$config['banco']['charset'] = 'utf8';
$config['banco']['servidor'] = 'localhost';
$config['banco']['usuario'] = 'root';
$config['banco']['senha'] = 'root';
$config['banco']['nome'] = 'miti_api';

spl_autoload_register(function($Classe) {
    $Classe = str_replace('\\', '/', $Classe);
    $Classe = str_replace('Miti', 'src', $Classe);
    $arquivo = __DIR__."/../$Classe.php";
    
    if (file_exists($arquivo)) {
        require_once $arquivo;
    }
});
