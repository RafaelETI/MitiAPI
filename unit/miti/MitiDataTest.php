<?php
class MitiDataTest extends PHPUnit_Framework_TestCase{
	public function testInverterBrParaEuaVazio(){
		$this->assertSame(null,MitiData::inverterBrParaEua(''));
	}
	
	public function testInverterBrParaEua(){
		$this->assertSame(
			'1991-08-18',MitiData::inverterBrParaEua('18/08/1991 14:34:02')
		);
	}
	
	public function testInverterEuaParaBrVazio(){
		$this->assertSame(null,MitiData::inverterEuaParaBr(''));
	}
	
	public function testInverterEuaParaBr(){
		$this->assertSame(
			'18/08/1991',MitiData::inverterEuaParaBr('1991-08-18 14:34:02')
		);
	}
	
	public function testObterDiaDaSemanaVazio(){
		$this->assertSame(null,MitiData::obterDiaDaSemana(''));
	}
	
	public function testObterDiaDaSemanaDomingo(){
		$this->assertSame('Dom',MitiData::obterDiaDaSemana('1991-08-18'));
	}
	
	public function testObterDiaDaSemanaSegunda(){
		$this->assertSame('Seg',MitiData::obterDiaDaSemana('1991-08-19'));
	}
	
	public function testObterDiaDaSemanaTerca(){
		$this->assertSame('Ter',MitiData::obterDiaDaSemana('1991-08-20'));
	}
	
	public function testObterDiaDaSemanaQuarta(){
		$this->assertSame('Qua',MitiData::obterDiaDaSemana('1991-08-21'));
	}
	
	public function testObterDiaDaSemanaQuinta(){
		$this->assertSame('Qui',MitiData::obterDiaDaSemana('1991-08-22'));
	}
	
	public function testObterDiaDaSemanaSexta(){
		$this->assertSame('Sex',MitiData::obterDiaDaSemana('1991-08-23'));
	}
	
	public function testObterDiaDaSemanaSabado(){
		$this->assertSame('Sáb',MitiData::obterDiaDaSemana('1991-08-24'));
	}
	
	public function testObterMesVazio(){
		$this->assertSame(null,MitiData::obterMes(''));
	}
	
	public function testObterMesJaneiro(){
		$this->assertSame('Jan.',MitiData::obterMes('2014-01-01'));
	}
	
	public function testObterMesFevereiro(){
		$this->assertSame('Fev.',MitiData::obterMes('2014-02-01'));
	}
	
	public function testObterMesMarco(){
		$this->assertSame('Mar.',MitiData::obterMes('2014-03-01'));
	}
	
	public function testObterMesAbril(){
		$this->assertSame('Abr.',MitiData::obterMes('2014-04-01'));
	}
	
	public function testObterMesMaio(){
		$this->assertSame('Mai.',MitiData::obterMes('2014-05-01'));
	}
	
	public function testObterMesJunho(){
		$this->assertSame('Jun.',MitiData::obterMes('2014-06-01'));
	}
	
	public function testObterMesJulho(){
		$this->assertSame('Jul.',MitiData::obterMes('2014-07-01'));
	}
	
	public function testObterMesAgosto(){
		$this->assertSame('Ago.',MitiData::obterMes('2014-08-01'));
	}
	
	public function testObterMesSetembro(){
		$this->assertSame('Set.',MitiData::obterMes('2014-09-01'));
	}
	
	public function testObterMesOutubro(){
		$this->assertSame('Out.',MitiData::obterMes('2014-10-01'));
	}
	
	public function testObterMesNovembro(){
		$this->assertSame('Nov.',MitiData::obterMes('2014-11-01'));
	}
	
	public function testObterMesDezembro(){
		$this->assertSame('Dez.',MitiData::obterMes('2014-12-01'));
	}
	
	public function testObterAnoVazio(){
		$this->assertSame(null,MitiData::obterAno(''));
	}
	
	public function testObterAno(){
		$this->assertSame('2014',MitiData::obterAno('2014-06-08'));
	}
}
