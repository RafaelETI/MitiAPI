<?php
namespace Miti;

class Status{
	public static function alertar(){
		if(!isset($_SESSION['status'])){return;}
		if($_SESSION['status'] === true){$_SESSION['status'] = 'Concluído com sucesso.';}
		
		$_SESSION['status'] = addslashes($_SESSION['status']);
		
		$alerta = "<script>alert('{$_SESSION['status']}');</script>";
		unset($_SESSION['status']);
		
		return $alerta;
	}
}
