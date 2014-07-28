<?php
class DesempenhoTest extends PHPUnit_Framework_TestCase{
	public function testMedirTempoDeExecucao(){
		$tempo=Miti\Desempenho::medirTempoDeExecucao(array(1391905903.114,1391905984.1241));
		$this->assertSame('81.010',$tempo);
	}
}
