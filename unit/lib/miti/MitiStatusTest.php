<?php
class MitiStatusTest extends PHPUnit_Framework_TestCase{
	protected $MitiStatus;
	
	protected function setUp(){
		$this->MitiStatus=new MitiStatus;
	}
	
	public function testObterAlertaComSucesso(){
		$teste='<script>';
		$teste.='alert("Concluído com sucesso");';
		$teste.='location.href="teste.php";';
		$teste.='</script>';
		
		$this->assertSame($teste,$this->MitiStatus->obterAlerta(true,'teste.php'));
	}
	
	public function testObterAlertaComErro(){
		$teste='<script>';
		$teste.='alert("Teste");';
		$teste.='location.href="teste.php";';
		$teste.='</script>';
		
		$this->assertSame($teste,$this->MitiStatus->obterAlerta('Teste','teste.php'));
	}
}