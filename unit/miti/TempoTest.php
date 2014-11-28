<?php
class TempoTest extends PHPUnit_Framework_TestCase{
	public function testBRUSVazio(){
		$this->assertSame(null, \miti\Tempo::brUS(''));
	}
	
	public function testBRUS(){
		$tempo = \miti\Tempo::brUS('18/08/1991 14:34:02');
		$this->assertSame('1991-08-18', $tempo);
	}
	
	public function testUSBRVazio(){
		$this->assertSame(null, \miti\Tempo::usBR(''));
	}
	
	public function testUSBR(){
		$tempo = \miti\Tempo::usBR('1991-08-18 14:34:02');
		$this->assertSame('18/08/1991', $tempo);
	}
	
	public function testDiaVazio(){
		$this->assertSame(null, \miti\Tempo::dia(''));
	}
	
	public function testDia(){
		$this->assertSame('08', \miti\Tempo::dia('2014-06-08'));
	}
	
	public function testDiaDaSemanaVazio(){
		$this->assertSame(null, \miti\Tempo::diaDaSemana(''));
	}
	
	public function testDomingo(){
		$this->assertSame('Dom', \miti\Tempo::diaDaSemana('1991-08-18'));
	}
	
	public function testSegunda(){
		$this->assertSame('Seg', \miti\Tempo::diaDaSemana('1991-08-19'));
	}
	
	public function testTerca(){
		$this->assertSame('Ter', \miti\Tempo::diaDaSemana('1991-08-20'));
	}
	
	public function testQuarta(){
		$this->assertSame('Qua', \miti\Tempo::diaDaSemana('1991-08-21'));
	}
	
	public function testQuinta(){
		$this->assertSame('Qui', \miti\Tempo::diaDaSemana('1991-08-22'));
	}
	
	public function testSexta(){
		$this->assertSame('Sex', \miti\Tempo::diaDaSemana('1991-08-23'));
	}
	
	public function testSabado(){
		$this->assertSame('Sáb', \miti\Tempo::diaDaSemana('1991-08-24'));
	}
	
	public function testMesVazio(){
		$this->assertSame(null, \miti\Tempo::mes(''));
	}
	
	public function testJaneiro(){
		$this->assertSame('Jan.', \miti\Tempo::mes('2014-01-01'));
	}
	
	public function testFevereiro(){
		$this->assertSame('Fev.', \miti\Tempo::mes('2014-02-01'));
	}
	
	public function testMarco(){
		$this->assertSame('Mar.', \miti\Tempo::mes('2014-03-01'));
	}
	
	public function testAbril(){
		$this->assertSame('Abr.', \miti\Tempo::mes('2014-04-01'));
	}
	
	public function testMaio(){
		$this->assertSame('Mai.', \miti\Tempo::mes('2014-05-01'));
	}
	
	public function testJunho(){
		$this->assertSame('Jun.', \miti\Tempo::mes('2014-06-01'));
	}
	
	public function testJulho(){
		$this->assertSame('Jul.', \miti\Tempo::mes('2014-07-01'));
	}
	
	public function testAgosto(){
		$this->assertSame('Ago.', \miti\Tempo::mes('2014-08-01'));
	}
	
	public function testSetembro(){
		$this->assertSame('Set.', \miti\Tempo::mes('2014-09-01'));
	}
	
	public function testOutubro(){
		$this->assertSame('Out.', \miti\Tempo::mes('2014-10-01'));
	}
	
	public function testNovembro(){
		$this->assertSame('Nov.', \miti\Tempo::mes('2014-11-01'));
	}
	
	public function testDezembro(){
		$this->assertSame('Dez.', \miti\Tempo::mes('2014-12-01'));
	}
	
	public function testAnoVazio(){
		$this->assertSame(null, \miti\Tempo::ano(''));
	}
	
	public function testAno(){
		$this->assertSame('2014', \miti\Tempo::ano('2014-06-08'));
	}
	
	public function testSomar(){
		$Intervalo = new \DateInterval('PT1H42M23S');
		$Intervalo2 = new \DateInterval('PT2M57S');
		
		$this->assertSame('01:45:20', \miti\Tempo::somar($Intervalo, $Intervalo2)->format('%H:%I:%S'));
	}
	
	public function testSubtrair(){
		$Intervalo = new \DateInterval('PT1H42M23S');
		$Intervalo2 = new \DateInterval('PT2M57S');
		
		$this->assertSame('01:39:26', \miti\Tempo::subtrair($Intervalo, $Intervalo2)->format('%H:%I:%S'));
	}
}
