<?php
class MitiStatusTest extends PHPUnit_Framework_TestCase{
	protected $MitiStatus;
	
	protected function setUp(){
		$this->MitiStatus=new MitiStatus;
	}
	
	public function testObterAlertaSemSessao(){
		$this->assertSame(null,$this->MitiStatus->obterAlerta());
	}
	
	public function testObterAlertaComSucesso(){
		$_SESSION['status']=true;
		$teste='<script>alert("Concluído com sucesso");</script>';
		$this->assertSame($teste,$this->MitiStatus->obterAlerta());
	}
	
	public function testObterAlertaComErro(){
		$_SESSION['status']='Erro';
		$teste='<script>alert("Erro");</script>';
		$this->assertSame($teste,$this->MitiStatus->obterAlerta());
	}
}