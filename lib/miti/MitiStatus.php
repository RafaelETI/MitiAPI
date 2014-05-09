<?php
class MitiStatus{
	public function obterAlerta(){
		if(!isset($_SESSION['status'])){
			return;
		}
		
		if($_SESSION['status']===true){
			$_SESSION['status']='Concluído com sucesso';
		}
		
		$_SESSION['status']=addslashes($_SESSION['status']);
		$alerta='<script>alert("'.$_SESSION['status'].'");</script>';
		unset($_SESSION['status']);
		
		return $alerta;
	}
}
