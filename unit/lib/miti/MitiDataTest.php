<?php
class MitiDataTest extends PHPUnit_Framework_TestCase{
	protected $MitiData;
	
	protected function setUp(){
		$this->MitiData=new MitiData;
	}
	
	public function testObterDiaSemana(){
		$this->assertSame(null,$this->MitiData->obterDiaSemana(''));
		$this->assertSame('Dom',$this->MitiData->obterDiaSemana('1991-08-18'));
		$this->assertSame('Seg',$this->MitiData->obterDiaSemana('1991-08-19'));
		$this->assertSame('Ter',$this->MitiData->obterDiaSemana('1991-08-20'));
		$this->assertSame('Qua',$this->MitiData->obterDiaSemana('1991-08-21'));
		$this->assertSame('Qui',$this->MitiData->obterDiaSemana('1991-08-22'));
		$this->assertSame('Sex',$this->MitiData->obterDiaSemana('1991-08-23'));
		$this->assertSame('Sáb',$this->MitiData->obterDiaSemana('1991-08-24'));
	}
	
	public function testObterMes(){
		$this->assertSame(null,$this->MitiData->obterMes(''));
		$this->assertSame('Janeiro',$this->MitiData->obterMes('01'));
		$this->assertSame('Fevereiro',$this->MitiData->obterMes('02'));
		$this->assertSame('Março',$this->MitiData->obterMes('03'));
		$this->assertSame('Abril',$this->MitiData->obterMes('04'));
		$this->assertSame('Maio',$this->MitiData->obterMes('05'));
		$this->assertSame('Junho',$this->MitiData->obterMes('06'));
		$this->assertSame('Julho',$this->MitiData->obterMes('07'));
		$this->assertSame('Agosto',$this->MitiData->obterMes('08'));
		$this->assertSame('Setembro',$this->MitiData->obterMes('09'));
		$this->assertSame('Outubro',$this->MitiData->obterMes('10'));
		$this->assertSame('Novembro',$this->MitiData->obterMes('11'));
		$this->assertSame('Dezembro',$this->MitiData->obterMes('12'));
	}
}