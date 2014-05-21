<?php
class MitiStatusTest extends PHPUnit_Framework_TestCase{
	private $MitiStatus;
	
	protected function setUp(){
		$this->MitiStatus=new MitiStatus;
	}
	
	public function testObterAlertaSemSessao(){
		$this->assertSame(null,$this->MitiStatus->obterAlerta());
	}
	
	public function testObterAlertaComSucesso(){
		$_SESSION['status']=true;
		
		$this->assertSame(
			'<script>alert("Concluído com sucesso");</script>',
			$this->MitiStatus->obterAlerta()
		);
	}
	
	public function testObterAlertaComErro(){
		$_SESSION['status']='Erro';
		
		$this->assertSame(
			'<script>alert("Erro");</script>',$this->MitiStatus->obterAlerta()
		);
	}
}
