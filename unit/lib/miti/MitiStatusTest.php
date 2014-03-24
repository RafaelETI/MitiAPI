<?php
class MitiStatusTest extends PHPUnit_Framework_TestCase{
	protected $MitiStatus;
	
	protected function setUp(){
		require_once 'Config.php';
		Config::setInstance();
		
		$this->MitiStatus=new MitiStatus;
	}
	
	public function testObterMensagem(){
		$_SESSION['status']=true;
		$teste='O procedimento foi realizado com sucesso';
		$this->assertSame($teste,$this->MitiStatus->obterMensagem());
		unset($_SESSION['status']);
	}
	
	public function testObterMensagemSemSessao(){
		$this->assertSame(null,$this->MitiStatus->obterMensagem());
	}
	
	public function testObterAlerta(){
		$teste='<script>alert("teste"); location.href="teste.php";</script>';
		$this->assertSame($teste,$this->MitiStatus->obterAlerta('teste','teste.php'));
	}
}