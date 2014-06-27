<?php
class MitiDesempenhoTest extends PHPUnit_Framework_TestCase{
	public function testMedirTempoExecucao(){
		$this->assertSame(
			'81.010',
			MitiDesempenho::medirTempoExecucao(array(1391905903.114,1391905984.1241))
		);
	}
}
