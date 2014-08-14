<?php
class TempoTest extends PHPUnit_Framework_TestCase{
	public function testInverterBrParaEuaVazio(){
		$this->assertSame(null,\miti\Tempo::inverterBrParaEua(''));
	}
	
	public function testInverterBrParaEua(){
		$tempo=\miti\Tempo::inverterBrParaEua('18/08/1991 14:34:02');
		$this->assertSame('1991-08-18',$tempo);
	}
	
	public function testInverterEuaParaBrVazio(){
		$this->assertSame(null,\miti\Tempo::inverterEuaParaBr(''));
	}
	
	public function testInverterEuaParaBr(){
		$tempo=\miti\Tempo::inverterEuaParaBr('1991-08-18 14:34:02');
		$this->assertSame('18/08/1991',$tempo);
	}
	
	public function testGerarValorRelativoVazio(){
		$hora=\miti\Tempo::gerarValorRelativo('');
		$this->assertSame(null,$hora);
	}
	
	public function testGerarValorRelativo(){
		$hora=\miti\Tempo::gerarValorRelativo('14:34:02');
		$this->assertSame('14 hour 34 min 02 sec',$hora);
	}
	
	public function testObterDiaDaSemanaVazio(){
		$this->assertSame(null,\miti\Tempo::obterDiaDaSemana(''));
	}
	
	public function testObterDomingo(){
		$this->assertSame('Dom',\miti\Tempo::obterDiaDaSemana('1991-08-18'));
	}
	
	public function testObterSegunda(){
		$this->assertSame('Seg',\miti\Tempo::obterDiaDaSemana('1991-08-19'));
	}
	
	public function testObterTerca(){
		$this->assertSame('Ter',\miti\Tempo::obterDiaDaSemana('1991-08-20'));
	}
	
	public function testObterQuarta(){
		$this->assertSame('Qua',\miti\Tempo::obterDiaDaSemana('1991-08-21'));
	}
	
	public function testObterQuinta(){
		$this->assertSame('Qui',\miti\Tempo::obterDiaDaSemana('1991-08-22'));
	}
	
	public function testObterSexta(){
		$this->assertSame('Sex',\miti\Tempo::obterDiaDaSemana('1991-08-23'));
	}
	
	public function testObterSabado(){
		$this->assertSame('Sáb',\miti\Tempo::obterDiaDaSemana('1991-08-24'));
	}
	
	public function testObterMesVazio(){
		$this->assertSame(null,\miti\Tempo::obterMes(''));
	}
	
	public function testObterJaneiro(){
		$this->assertSame('Jan.',\miti\Tempo::obterMes('2014-01-01'));
	}
	
	public function testObterFevereiro(){
		$this->assertSame('Fev.',\miti\Tempo::obterMes('2014-02-01'));
	}
	
	public function testObterMarco(){
		$this->assertSame('Mar.',\miti\Tempo::obterMes('2014-03-01'));
	}
	
	public function testObterAbril(){
		$this->assertSame('Abr.',\miti\Tempo::obterMes('2014-04-01'));
	}
	
	public function testObterMaio(){
		$this->assertSame('Mai.',\miti\Tempo::obterMes('2014-05-01'));
	}
	
	public function testObterJunho(){
		$this->assertSame('Jun.',\miti\Tempo::obterMes('2014-06-01'));
	}
	
	public function testObterJulho(){
		$this->assertSame('Jul.',\miti\Tempo::obterMes('2014-07-01'));
	}
	
	public function testObterAgosto(){
		$this->assertSame('Ago.',\miti\Tempo::obterMes('2014-08-01'));
	}
	
	public function testObterSetembro(){
		$this->assertSame('Set.',\miti\Tempo::obterMes('2014-09-01'));
	}
	
	public function testObterOutubro(){
		$this->assertSame('Out.',\miti\Tempo::obterMes('2014-10-01'));
	}
	
	public function testObterNovembro(){
		$this->assertSame('Nov.',\miti\Tempo::obterMes('2014-11-01'));
	}
	
	public function testObterDezembro(){
		$this->assertSame('Dez.',\miti\Tempo::obterMes('2014-12-01'));
	}
	
	public function testObterAnoVazio(){
		$this->assertSame(null,\miti\Tempo::obterAno(''));
	}
	
	public function testObterAno(){
		$this->assertSame('2014',\miti\Tempo::obterAno('2014-06-08'));
	}
}
