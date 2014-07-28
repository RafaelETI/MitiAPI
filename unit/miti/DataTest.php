<?php
class DataTest extends PHPUnit_Framework_TestCase{
	public function testInverterBrParaEuaVazio(){
		$this->assertSame(null,Miti\Data::inverterBrParaEua(''));
	}
	
	public function testInverterBrParaEua(){
		$data=Miti\Data::inverterBrParaEua('18/08/1991 14:34:02');
		$this->assertSame('1991-08-18',$data);
	}
	
	public function testInverterEuaParaBrVazio(){
		$this->assertSame(null,Miti\Data::inverterEuaParaBr(''));
	}
	
	public function testInverterEuaParaBr(){
		$data=Miti\Data::inverterEuaParaBr('1991-08-18 14:34:02');
		$this->assertSame('18/08/1991',$data);
	}
	
	public function testObterDiaDaSemanaVazio(){
		$this->assertSame(null,Miti\Data::obterDiaDaSemana(''));
	}
	
	public function testObterDomingo(){
		$this->assertSame('Dom',Miti\Data::obterDiaDaSemana('1991-08-18'));
	}
	
	public function testObterSegunda(){
		$this->assertSame('Seg',Miti\Data::obterDiaDaSemana('1991-08-19'));
	}
	
	public function testObterTerca(){
		$this->assertSame('Ter',Miti\Data::obterDiaDaSemana('1991-08-20'));
	}
	
	public function testObterQuarta(){
		$this->assertSame('Qua',Miti\Data::obterDiaDaSemana('1991-08-21'));
	}
	
	public function testObterQuinta(){
		$this->assertSame('Qui',Miti\Data::obterDiaDaSemana('1991-08-22'));
	}
	
	public function testObterSexta(){
		$this->assertSame('Sex',Miti\Data::obterDiaDaSemana('1991-08-23'));
	}
	
	public function testObterSabado(){
		$this->assertSame('Sáb',Miti\Data::obterDiaDaSemana('1991-08-24'));
	}
	
	public function testObterMesVazio(){
		$this->assertSame(null,Miti\Data::obterMes(''));
	}
	
	public function testObterJaneiro(){
		$this->assertSame('Jan.',Miti\Data::obterMes('2014-01-01'));
	}
	
	public function testObterFevereiro(){
		$this->assertSame('Fev.',Miti\Data::obterMes('2014-02-01'));
	}
	
	public function testObterMarco(){
		$this->assertSame('Mar.',Miti\Data::obterMes('2014-03-01'));
	}
	
	public function testObterAbril(){
		$this->assertSame('Abr.',Miti\Data::obterMes('2014-04-01'));
	}
	
	public function testObterMaio(){
		$this->assertSame('Mai.',Miti\Data::obterMes('2014-05-01'));
	}
	
	public function testObterJunho(){
		$this->assertSame('Jun.',Miti\Data::obterMes('2014-06-01'));
	}
	
	public function testObterJulho(){
		$this->assertSame('Jul.',Miti\Data::obterMes('2014-07-01'));
	}
	
	public function testObterAgosto(){
		$this->assertSame('Ago.',Miti\Data::obterMes('2014-08-01'));
	}
	
	public function testObterSetembro(){
		$this->assertSame('Set.',Miti\Data::obterMes('2014-09-01'));
	}
	
	public function testObterOutubro(){
		$this->assertSame('Out.',Miti\Data::obterMes('2014-10-01'));
	}
	
	public function testObterNovembro(){
		$this->assertSame('Nov.',Miti\Data::obterMes('2014-11-01'));
	}
	
	public function testObterDezembro(){
		$this->assertSame('Dez.',Miti\Data::obterMes('2014-12-01'));
	}
	
	public function testObterAnoVazio(){
		$this->assertSame(null,Miti\Data::obterAno(''));
	}
	
	public function testObterAno(){
		$this->assertSame('2014',Miti\Data::obterAno('2014-06-08'));
	}
}
