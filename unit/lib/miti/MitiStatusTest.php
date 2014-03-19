<?php
class MitiStatusTest extends PHPUnit_Framework_TestCase{
	protected $MitiStatus;
	
	protected function setUp(){
		require_once 'Config.php';
		Config::setInstance();
		
		$this->MitiStatus=new MitiStatus;
	}
	
	public function testObterMensagem(){
		$this->obterMensagemSemSessao();
		
		$_SESSION['status']=true;
		$afirmacao='O procedimento foi realizado com sucesso';
		$this->assertSame($afirmacao,$this->MitiStatus->obterMensagem());
		unset($_SESSION['status']);
	}
	
	private function obterMensagemSemSessao(){
		$this->assertSame(null,$this->MitiStatus->obterMensagem());
	}
	
	public function testObterAlerta(){
		$afirmacao='<script>alert("teste"); location.href="teste.php";</script>';
		$this->assertSame($afirmacao,$this->MitiStatus->obterAlerta('teste','teste.php'));
	}
}