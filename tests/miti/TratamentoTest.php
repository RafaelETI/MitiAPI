<?php
class TratamentoTest extends PHPUnit_Framework_TestCase{
	public function testRequererJs(){
		$padrao = "/^<script src='.+\/Miti\.js\?hash=[a-f\d]{32}'><\/script>\\n$/i";
		$requerimento = \miti\Tratamento::requerer('../tests/arquivos/Miti.js');
		$this->assertSame(1, preg_match($padrao, $requerimento));
	}
	
	public function testRequererCss(){
		$padrao = "/^<link rel='stylesheet' href='.+\/miti\.css\?hash=[a-f\d]{32}' \/>\\n$/i";
		$requerimento = \miti\Tratamento::requerer('../tests/arquivos/miti.css');
		$this->assertSame(1, preg_match($padrao, $requerimento));
	}
	
	public function testIndexar(){
		$vetor = array();
		$vetor = \miti\Tratamento::indexar($vetor, array('teste'));
		$this->assertTrue(isset($vetor['teste']));
	}
	
	public function testEscaparVazio(){
		$this->assertSame(null, \miti\Tratamento::escapar(''));
	}
	
	public function testEscaparArray(){
		$esperados = array('&#039;', '&quot;', '&amp;', '&lt;', '&gt;');
		$escapados = \miti\Tratamento::escapar(array("'", '"', '&', '<', '>'));
		$this->assertSame($esperados, $escapados);
	}
	
	public function testEscaparScalar(){
		$esperado = '&#039;&quot;&amp;&lt;&gt;';
		$this->assertSame($esperado, \miti\Tratamento::escapar('\'"&<>'));
	}
	
	public function testEncurtarVazio(){
		$this->assertSame(null, \miti\Tratamento::encurtar('', 10));
	}
	
	public function testEncurtarArray(){
		$esperados = array('aaaaa...', 'bbbbb...', 'ccccc...');
		$curtos = \miti\Tratamento::encurtar(array('aaaaaaaaaa', 'bbbbbbbbbb', 'cccccccccc'), 5);
		$this->assertSame($esperados, $curtos);
	}
	
	public function testEncurtarScalar(){
		$this->assertSame('aaa...', \miti\Tratamento::encurtar('aaaaaaaaaa', 3));
	}
}
