<?php
class MitiDesempenhoTest extends PHPUnit_Framework_TestCase{
	public function testMedirTempoDeExecucao(){
		$tempo=MitiDesempenho::medirTempoDeExecucao(array(1391905903.114,1391905984.1241));
		$this->assertSame('81.010',$tempo);
	}
}
