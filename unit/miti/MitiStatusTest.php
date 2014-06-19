<?php
class MitiStatusTest extends PHPUnit_Framework_TestCase{
	private $MitiStatus;
	
	protected function setUp(){
		$this->MitiStatus=new MitiStatus;
	}
	
	public function testAlertarSemSessao(){
		$this->assertSame(null,$this->MitiStatus->alertar());
	}
	
	public function testAlertarComSucesso(){
		$_SESSION['status']=true;
		
		$this->assertSame(
			'<script>alert("Concluído com sucesso.");</script>',
			$this->MitiStatus->alertar()
		);
	}
	
	public function testAlertarComErro(){
		$_SESSION['status']='Erro.';
		
		$this->assertSame(
			'<script>alert("Erro.");</script>',$this->MitiStatus->alertar()
		);
	}
}
