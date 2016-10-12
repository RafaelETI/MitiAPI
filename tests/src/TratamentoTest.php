<?php
class TratamentoTest extends PHPUnit_Framework_TestCase{
	public function testRequererJs(){
		$padrao = "/^<script src='.+\/Miti\.js\?hash=[a-f\d]{32}'><\/script>\\n$/i";
		$requerimento = \Miti\Tratamento::requerer('/../tests/arquivos/Miti.js');
		$this->assertSame(1, preg_match($padrao, $requerimento));
	}
	
	public function testRequererCss(){
		$padrao = "/^<link rel='stylesheet' href='.+\/miti\.css\?hash=[a-f\d]{32}' \/>\\n$/i";
		$requerimento = \Miti\Tratamento::requerer('/../tests/arquivos/miti.css');
		$this->assertSame(1, preg_match($padrao, $requerimento));
	}
	
	public function testIndexar(){
		$vetor = array();
		$vetor = \Miti\Tratamento::indexar($vetor, array('teste'));
		$this->assertTrue(isset($vetor['teste']));
	}
	
	public function testEscaparVazio(){
		$this->assertSame(null, \Miti\Tratamento::escapar(''));
	}
	
	public function testEscaparArray(){
		$esperados = array('&#039;', '&quot;', '&amp;', '&lt;', '&gt;');
		$escapados = \Miti\Tratamento::escapar(array("'", '"', '&', '<', '>'));
		$this->assertSame($esperados, $escapados);
	}
	
	public function testEscaparScalar(){
		$esperado = '&#039;&quot;&amp;&lt;&gt;';
		$this->assertSame($esperado, \Miti\Tratamento::escapar('\'"&<>'));
	}
	
	public function testEncurtarVazio(){
		$this->assertSame(null, \Miti\Tratamento::encurtar('', 10));
	}
	
	public function testEncurtarArray(){
		$esperados = array('aaaaa...', 'bbbbb...', 'ccccc...');
		$curtos = \Miti\Tratamento::encurtar(array('aaaaaaaaaa', 'bbbbbbbbbb', 'cccccccccc'), 5);
		$this->assertSame($esperados, $curtos);
	}
	
	public function testEncurtarScalar(){
		$this->assertSame('aaa...', \Miti\Tratamento::encurtar('aaaaaaaaaa', 3));
	}
}
