<?php
class MitiDesempenhoTest extends PHPUnit_Framework_TestCase{
	private $MitiDesempenho;
	
	protected function setUp(){
		$this->MitiDesempenho=new MitiDesempenho;
	}
	
	public function testMedirTempoExecucao(){
		$this->assertSame(
			'81.010',
		
			$this->MitiDesempenho->medirTempoExecucao(
				array(1391905903.114,1391905984.1241)
			)
		);
	}
}
