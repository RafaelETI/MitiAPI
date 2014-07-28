<?php
class StatusTest extends PHPUnit_Framework_TestCase{
	public function testAlertarSemSessao(){
		$this->assertSame(null,Miti\Status::alertar());
	}
	
	public function testAlertarComSucesso(){
		$_SESSION['status']=true;
		$js='<script>alert("Concluído com sucesso.");</script>';
		$this->assertSame($js,Miti\Status::alertar());
	}
	
	public function testAlertarComErro(){
		$_SESSION['status']='Erro.';
		$this->assertSame('<script>alert("Erro.");</script>',Miti\Status::alertar());
	}
}
