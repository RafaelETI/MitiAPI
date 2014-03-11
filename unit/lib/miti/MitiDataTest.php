<?php
require_once 'Config.php'; new Config;

class MitiDataTest extends PHPUnit_Framework_TestCase{
	protected $MitiData;
	
	protected function setUp(){
		$this->MitiData=new MitiData;
	}
	
	public function testBr2Eua(){
		$teste='18/08/1991';
		$this->MitiData->br2Eua($teste);
		$this->assertSame('1991-08-18',$teste);
	}
	
	public function testEua2Br(){
		$teste='1991-08-18';
		$this->MitiData->eua2Br($teste);
		$this->assertSame('18/08/1991',$teste);
	}
	
	public function testObterDiaSemana(){
		$this->assertSame($this->MitiData->obterDiaSemana('1991-08-23'),'Sex');
	}
	
	public function testObterMes(){
		$teste='08';
		$this->MitiData->obterMes($teste);
		$this->assertSame('Agosto',$teste);
	}
}