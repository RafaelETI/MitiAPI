<?php
class MitiDataTest extends PHPUnit_Framework_TestCase{
	private $MitiData;
	
	protected function setUp(){
		$this->MitiData=new MitiData;
	}
	
	public function testInverterBrParaEuaVazio(){
		$this->assertSame(null,$this->MitiData->inverterBrParaEua(''));
	}
	
	public function testInverterBrParaEua(){
		$this->assertSame(
			'1991-08-18',$this->MitiData->inverterBrParaEua('18/08/1991 14:34:02')
		);
	}
	
	public function testInverterEuaParaBrVazio(){
		$this->assertSame(null,$this->MitiData->inverterEuaParaBr(''));
	}
	
	public function testInverterEuaParaBr(){
		$this->assertSame(
			'18/08/1991',$this->MitiData->inverterEuaParaBr('1991-08-18 14:34:02')
		);
	}
	
	public function testObterDiaSemanaVazio(){
		$this->assertSame(null,$this->MitiData->obterDiaSemana(''));
	}
	
	public function testObterDiaSemanaDomingo(){
		$this->assertSame('Dom',$this->MitiData->obterDiaSemana('1991-08-18'));
	}
	
	public function testObterDiaSemanaSegunda(){
		$this->assertSame('Seg',$this->MitiData->obterDiaSemana('1991-08-19'));
	}
	
	public function testObterDiaSemanaTerca(){
		$this->assertSame('Ter',$this->MitiData->obterDiaSemana('1991-08-20'));
	}
	
	public function testObterDiaSemanaQuarta(){
		$this->assertSame('Qua',$this->MitiData->obterDiaSemana('1991-08-21'));
	}
	
	public function testObterDiaSemanaQuinta(){
		$this->assertSame('Qui',$this->MitiData->obterDiaSemana('1991-08-22'));
	}
	
	public function testObterDiaSemanaSexta(){
		$this->assertSame('Sex',$this->MitiData->obterDiaSemana('1991-08-23'));
	}
	
	public function testObterDiaSemanaSabado(){
		$this->assertSame('Sáb',$this->MitiData->obterDiaSemana('1991-08-24'));
	}
	
	public function testObterMesVazio(){
		$this->assertSame(null,$this->MitiData->obterMes(''));
	}
	
	public function testObterMesJaneiro(){
		$this->assertSame('Jan.',$this->MitiData->obterMes('2014-01-01'));
	}
	
	public function testObterMesFevereiro(){
		$this->assertSame('Fev.',$this->MitiData->obterMes('2014-02-01'));
	}
	
	public function testObterMesMarco(){
		$this->assertSame('Mar.',$this->MitiData->obterMes('2014-03-01'));
	}
	
	public function testObterMesAbril(){
		$this->assertSame('Abr.',$this->MitiData->obterMes('2014-04-01'));
	}
	
	public function testObterMesMaio(){
		$this->assertSame('Mai.',$this->MitiData->obterMes('2014-05-01'));
	}
	
	public function testObterMesJunho(){
		$this->assertSame('Jun.',$this->MitiData->obterMes('2014-06-01'));
	}
	
	public function testObterMesJulho(){
		$this->assertSame('Jul.',$this->MitiData->obterMes('2014-07-01'));
	}
	
	public function testObterMesAgosto(){
		$this->assertSame('Ago.',$this->MitiData->obterMes('2014-08-01'));
	}
	
	public function testObterMesSetembro(){
		$this->assertSame('Set.',$this->MitiData->obterMes('2014-09-01'));
	}
	
	public function testObterMesOutubro(){
		$this->assertSame('Out.',$this->MitiData->obterMes('2014-10-01'));
	}
	
	public function testObterMesNovembro(){
		$this->assertSame('Nov.',$this->MitiData->obterMes('2014-11-01'));
	}
	
	public function testObterMesDezembro(){
		$this->assertSame('Dez.',$this->MitiData->obterMes('2014-12-01'));
	}
	
	public function testObterAnoVazio(){
		$this->assertSame(null,$this->MitiData->obterAno(''));
	}
	
	public function testObterAno(){
		$this->assertSame('2014',$this->MitiData->obterAno('2014-06-08'));
	}
}
