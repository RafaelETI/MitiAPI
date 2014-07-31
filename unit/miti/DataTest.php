<?php
class DataTest extends PHPUnit_Framework_TestCase{
	public function testInverterBrParaEuaVazio(){
		$this->assertSame(null,\miti\Data::inverterBrParaEua(''));
	}
	
	public function testInverterBrParaEua(){
		$data=\miti\Data::inverterBrParaEua('18/08/1991 14:34:02');
		$this->assertSame('1991-08-18',$data);
	}
	
	public function testInverterEuaParaBrVazio(){
		$this->assertSame(null,\miti\Data::inverterEuaParaBr(''));
	}
	
	public function testInverterEuaParaBr(){
		$data=\miti\Data::inverterEuaParaBr('1991-08-18 14:34:02');
		$this->assertSame('18/08/1991',$data);
	}
	
	public function testObterDiaDaSemanaVazio(){
		$this->assertSame(null,\miti\Data::obterDiaDaSemana(''));
	}
	
	public function testObterDomingo(){
		$this->assertSame('Dom',\miti\Data::obterDiaDaSemana('1991-08-18'));
	}
	
	public function testObterSegunda(){
		$this->assertSame('Seg',\miti\Data::obterDiaDaSemana('1991-08-19'));
	}
	
	public function testObterTerca(){
		$this->assertSame('Ter',\miti\Data::obterDiaDaSemana('1991-08-20'));
	}
	
	public function testObterQuarta(){
		$this->assertSame('Qua',\miti\Data::obterDiaDaSemana('1991-08-21'));
	}
	
	public function testObterQuinta(){
		$this->assertSame('Qui',\miti\Data::obterDiaDaSemana('1991-08-22'));
	}
	
	public function testObterSexta(){
		$this->assertSame('Sex',\miti\Data::obterDiaDaSemana('1991-08-23'));
	}
	
	public function testObterSabado(){
		$this->assertSame('Sáb',\miti\Data::obterDiaDaSemana('1991-08-24'));
	}
	
	public function testObterMesVazio(){
		$this->assertSame(null,\miti\Data::obterMes(''));
	}
	
	public function testObterJaneiro(){
		$this->assertSame('Jan.',\miti\Data::obterMes('2014-01-01'));
	}
	
	public function testObterFevereiro(){
		$this->assertSame('Fev.',\miti\Data::obterMes('2014-02-01'));
	}
	
	public function testObterMarco(){
		$this->assertSame('Mar.',\miti\Data::obterMes('2014-03-01'));
	}
	
	public function testObterAbril(){
		$this->assertSame('Abr.',\miti\Data::obterMes('2014-04-01'));
	}
	
	public function testObterMaio(){
		$this->assertSame('Mai.',\miti\Data::obterMes('2014-05-01'));
	}
	
	public function testObterJunho(){
		$this->assertSame('Jun.',\miti\Data::obterMes('2014-06-01'));
	}
	
	public function testObterJulho(){
		$this->assertSame('Jul.',\miti\Data::obterMes('2014-07-01'));
	}
	
	public function testObterAgosto(){
		$this->assertSame('Ago.',\miti\Data::obterMes('2014-08-01'));
	}
	
	public function testObterSetembro(){
		$this->assertSame('Set.',\miti\Data::obterMes('2014-09-01'));
	}
	
	public function testObterOutubro(){
		$this->assertSame('Out.',\miti\Data::obterMes('2014-10-01'));
	}
	
	public function testObterNovembro(){
		$this->assertSame('Nov.',\miti\Data::obterMes('2014-11-01'));
	}
	
	public function testObterDezembro(){
		$this->assertSame('Dez.',\miti\Data::obterMes('2014-12-01'));
	}
	
	public function testObterAnoVazio(){
		$this->assertSame(null,\miti\Data::obterAno(''));
	}
	
	public function testObterAno(){
		$this->assertSame('2014',\miti\Data::obterAno('2014-06-08'));
	}
}
