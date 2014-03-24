<?php
class MitiDesempenhoTest extends PHPUnit_Framework_TestCase{
	protected $MitiDesempenho;
	
	protected function setUp(){
		$this->MitiDesempenho=new MitiDesempenho;
	}
	
	public function testMedirTempoExecucao(){
		$teste=array(1391905903.114,1391905984.1241);
		$this->assertSame('81.010',$this->MitiDesempenho->medirTempoExecucao($teste));
	}
}