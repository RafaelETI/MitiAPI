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
		$this->assertSame('Janeiro',$this->MitiData->obterMes('01'));
	}
	
	public function testObterMesFevereiro(){
		$this->assertSame('Fevereiro',$this->MitiData->obterMes('02'));
	}
	
	public function testObterMesMarco(){
		$this->assertSame('Março',$this->MitiData->obterMes('03'));
	}
	
	public function testObterMesAbril(){
		$this->assertSame('Abril',$this->MitiData->obterMes('04'));
	}
	
	public function testObterMesMaio(){
		$this->assertSame('Maio',$this->MitiData->obterMes('05'));
	}
	
	public function testObterMesJunho(){
		$this->assertSame('Junho',$this->MitiData->obterMes('06'));
	}
	
	public function testObterMesJulho(){
		$this->assertSame('Julho',$this->MitiData->obterMes('07'));
	}
	
	public function testObterMesAgosto(){
		$this->assertSame('Agosto',$this->MitiData->obterMes('08'));
	}
	
	public function testObterMesSetembro(){
		$this->assertSame('Setembro',$this->MitiData->obterMes('09'));
	}
	
	public function testObterMesOutubro(){
		$this->assertSame('Outubro',$this->MitiData->obterMes('10'));
	}
	
	public function testObterMesNovembro(){
		$this->assertSame('Novembro',$this->MitiData->obterMes('11'));
	}
	
	public function testObterMesDezembro(){
		$this->assertSame('Dezembro',$this->MitiData->obterMes('12'));
	}
}
