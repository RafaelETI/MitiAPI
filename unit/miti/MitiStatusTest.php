<?php
class MitiStatusTest extends PHPUnit_Framework_TestCase{
	public function testAlertarSemSessao(){
		$this->assertSame(null,MitiStatus::alertar());
	}
	
	public function testAlertarComSucesso(){
		$_SESSION['status']=true;
		$js='<script>alert("Concluído com sucesso.");</script>';
		$this->assertSame($js,MitiStatus::alertar());
	}
	
	public function testAlertarComErro(){
		$_SESSION['status']='Erro.';
		$this->assertSame('<script>alert("Erro.");</script>',MitiStatus::alertar());
	}
}
