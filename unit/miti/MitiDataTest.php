<?php
class MitiDataTest extends PHPUnit_Framework_TestCase{
	public function testInverterBrParaEuaVazio(){
		$this->assertSame(null,MitiData::inverterBrParaEua(''));
	}
	
	public function testInverterBrParaEua(){
		$data=MitiData::inverterBrParaEua('18/08/1991 14:34:02');
		$this->assertSame('1991-08-18',$data);
	}
	
	public function testInverterEuaParaBrVazio(){
		$this->assertSame(null,MitiData::inverterEuaParaBr(''));
	}
	
	public function testInverterEuaParaBr(){
		$data=MitiData::inverterEuaParaBr('1991-08-18 14:34:02');
		$this->assertSame('18/08/1991',$data);
	}
	
	public function testObterDiaDaSemanaVazio(){
		$this->assertSame(null,MitiData::obterDiaDaSemana(''));
	}
	
	public function testObterDomingo(){
		$this->assertSame('Dom',MitiData::obterDiaDaSemana('1991-08-18'));
	}
	
	public function testObterSegunda(){
		$this->assertSame('Seg',MitiData::obterDiaDaSemana('1991-08-19'));
	}
	
	public function testObterTerca(){
		$this->assertSame('Ter',MitiData::obterDiaDaSemana('1991-08-20'));
	}
	
	public function testObterQuarta(){
		$this->assertSame('Qua',MitiData::obterDiaDaSemana('1991-08-21'));
	}
	
	public function testObterQuinta(){
		$this->assertSame('Qui',MitiData::obterDiaDaSemana('1991-08-22'));
	}
	
	public function testObterSexta(){
		$this->assertSame('Sex',MitiData::obterDiaDaSemana('1991-08-23'));
	}
	
	public function testObterSabado(){
		$this->assertSame('Sáb',MitiData::obterDiaDaSemana('1991-08-24'));
	}
	
	public function testObterMesVazio(){
		$this->assertSame(null,MitiData::obterMes(''));
	}
	
	public function testObterJaneiro(){
		$this->assertSame('Jan.',MitiData::obterMes('2014-01-01'));
	}
	
	public function testObterFevereiro(){
		$this->assertSame('Fev.',MitiData::obterMes('2014-02-01'));
	}
	
	public function testObterMarco(){
		$this->assertSame('Mar.',MitiData::obterMes('2014-03-01'));
	}
	
	public function testObterAbril(){
		$this->assertSame('Abr.',MitiData::obterMes('2014-04-01'));
	}
	
	public function testObterMaio(){
		$this->assertSame('Mai.',MitiData::obterMes('2014-05-01'));
	}
	
	public function testObterJunho(){
		$this->assertSame('Jun.',MitiData::obterMes('2014-06-01'));
	}
	
	public function testObterJulho(){
		$this->assertSame('Jul.',MitiData::obterMes('2014-07-01'));
	}
	
	public function testObterAgosto(){
		$this->assertSame('Ago.',MitiData::obterMes('2014-08-01'));
	}
	
	public function testObterSetembro(){
		$this->assertSame('Set.',MitiData::obterMes('2014-09-01'));
	}
	
	public function testObterOutubro(){
		$this->assertSame('Out.',MitiData::obterMes('2014-10-01'));
	}
	
	public function testObterNovembro(){
		$this->assertSame('Nov.',MitiData::obterMes('2014-11-01'));
	}
	
	public function testObterDezembro(){
		$this->assertSame('Dez.',MitiData::obterMes('2014-12-01'));
	}
	
	public function testObterAnoVazio(){
		$this->assertSame(null,MitiData::obterAno(''));
	}
	
	public function testObterAno(){
		$this->assertSame('2014',MitiData::obterAno('2014-06-08'));
	}
}
