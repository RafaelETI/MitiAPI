<?php
class StatusTest extends PHPUnit_Framework_TestCase{
	public function testAlertarSemSessao(){
		$this->assertSame(null, \miti\Status::alertar());
	}
	
	public function testAlertarComSucesso(){
		$_SESSION['status'] = true;
		$js = "<script>alert('Concluído com sucesso.');</script>";
		$this->assertSame($js, \miti\Status::alertar());
	}
	
	public function testAlertarComErro(){
		$_SESSION['status'] = 'Erro.';
		$this->assertSame("<script>alert('Erro.');</script>", \miti\Status::alertar());
	}
}
