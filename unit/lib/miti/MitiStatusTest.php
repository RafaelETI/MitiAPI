<?php
require_once 'Config.php'; Config::setInstance();

class MitiStatusTest extends PHPUnit_Framework_TestCase{
	protected $MitiStatus;
	
	protected function setUp(){
		$this->MitiStatus=new MitiStatus;
	}
	
	public function testObterMensagem(){
		$_SESSION['status']=true;
		$afirmacao='O procedimento foi realizado com sucesso';
		$this->assertSame($afirmacao,$this->MitiStatus->obterMensagem());
		unset($_SESSION['status']);
	}
	
	public function testObterAlerta(){
		$afirmacao='<script>alert("teste"); location.href="teste.php";</script>';
		$this->assertSame($afirmacao,$this->MitiStatus->obterAlerta('teste','teste.php'));
	}
}