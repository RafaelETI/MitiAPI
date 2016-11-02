<?php
use PHPUnit\Framework\TestCase;

class TempoTest extends TestCase{
	public function testBRUSVazio(){
		$this->assertSame(null, \Miti\Tempo::brUS(''));
	}
	
	public function testBRUS(){
		$tempo = \Miti\Tempo::brUS('18/08/1991 14:34:02');
		$this->assertSame('1991-08-18', $tempo);
	}
	
	public function testUSBRVazio(){
		$this->assertSame(null, \Miti\Tempo::usBR(''));
	}
	
	public function testUSBR(){
		$tempo = \Miti\Tempo::usBR('1991-08-18 14:34:02');
		$this->assertSame('18/08/1991', $tempo);
	}
	
	public function testDiaVazio(){
		$this->assertSame(null, \Miti\Tempo::dia(''));
	}
	
	public function testDia(){
		$this->assertSame('08', \Miti\Tempo::dia('2014-06-08'));
	}
	
	public function testDiaDaSemanaVazio(){
		$this->assertSame(null, \Miti\Tempo::diaDaSemana(''));
	}
	
	public function testDomingo(){
		$this->assertSame('Dom', \Miti\Tempo::diaDaSemana('1991-08-18'));
	}
	
	public function testSegunda(){
		$this->assertSame('Seg', \Miti\Tempo::diaDaSemana('1991-08-19'));
	}
	
	public function testTerca(){
		$this->assertSame('Ter', \Miti\Tempo::diaDaSemana('1991-08-20'));
	}
	
	public function testQuarta(){
		$this->assertSame('Qua', \Miti\Tempo::diaDaSemana('1991-08-21'));
	}
	
	public function testQuinta(){
		$this->assertSame('Qui', \Miti\Tempo::diaDaSemana('1991-08-22'));
	}
	
	public function testSexta(){
		$this->assertSame('Sex', \Miti\Tempo::diaDaSemana('1991-08-23'));
	}
	
	public function testSabado(){
		$this->assertSame('Sáb', \Miti\Tempo::diaDaSemana('1991-08-24'));
	}
	
	public function testMesVazio(){
		$this->assertSame(null, \Miti\Tempo::mes(''));
	}
	
	public function testJaneiro(){
		$this->assertSame('Jan.', \Miti\Tempo::mes('2014-01-01'));
	}
	
	public function testFevereiro(){
		$this->assertSame('Fev.', \Miti\Tempo::mes('2014-02-01'));
	}
	
	public function testMarco(){
		$this->assertSame('Mar.', \Miti\Tempo::mes('2014-03-01'));
	}
	
	public function testAbril(){
		$this->assertSame('Abr.', \Miti\Tempo::mes('2014-04-01'));
	}
	
	public function testMaio(){
		$this->assertSame('Mai.', \Miti\Tempo::mes('2014-05-01'));
	}
	
	public function testJunho(){
		$this->assertSame('Jun.', \Miti\Tempo::mes('2014-06-01'));
	}
	
	public function testJulho(){
		$this->assertSame('Jul.', \Miti\Tempo::mes('2014-07-01'));
	}
	
	public function testAgosto(){
		$this->assertSame('Ago.', \Miti\Tempo::mes('2014-08-01'));
	}
	
	public function testSetembro(){
		$this->assertSame('Set.', \Miti\Tempo::mes('2014-09-01'));
	}
	
	public function testOutubro(){
		$this->assertSame('Out.', \Miti\Tempo::mes('2014-10-01'));
	}
	
	public function testNovembro(){
		$this->assertSame('Nov.', \Miti\Tempo::mes('2014-11-01'));
	}
	
	public function testDezembro(){
		$this->assertSame('Dez.', \Miti\Tempo::mes('2014-12-01'));
	}
	
	public function testAnoVazio(){
		$this->assertSame(null, \Miti\Tempo::ano(''));
	}
	
	public function testAno(){
		$this->assertSame('2014', \Miti\Tempo::ano('2014-06-08'));
	}
	
	public function testSomar(){
		$Intervalo = new \DateInterval('PT1H42M23S');
		$Intervalo2 = new \DateInterval('PT2M57S');
		
		$this->assertSame('01:45:20', \Miti\Tempo::somar($Intervalo, $Intervalo2)->format('%H:%I:%S'));
	}
	
	public function testSubtrair(){
		$Intervalo = new \DateInterval('PT1H42M23S');
		$Intervalo2 = new \DateInterval('PT2M57S');
		
		$this->assertSame('01:39:26', \Miti\Tempo::subtrair($Intervalo, $Intervalo2)->format('%H:%I:%S'));
	}
}
