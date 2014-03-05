<?php
class MitiStatusUnit extends MitiUnit{
	private $MitiStatus;
	
	public function __construct(){
		$this->MitiStatus=new MitiStatus();
		$this->obterMensagem();
		$this->obterAlerta();
	}
	
	private function obterMensagem(){
		$_SESSION['status']=true;
		$this->afirmar($this->MitiStatus->obterMensagem(),'O procedimento foi realizado com sucesso',__METHOD__);
		unset($_SESSION['status']);
	}
	
	private function obterAlerta(){
		$afirmacao='<script>alert("teste"); location.href="teste.php";</script>';
		$this->afirmar($this->MitiStatus->obterAlerta('teste','teste.php'),$afirmacao,__METHOD__);
	}
}
?>
