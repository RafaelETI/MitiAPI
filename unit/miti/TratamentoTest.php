<?php
class TratamentoTest extends PHPUnit_Framework_TestCase{
	public function testRequererJs(){
		$padrao = "/^<script src='arquivo\/Miti\.js\?hash=[a-f\d]{32}'><\/script>\\n$/i";
		$requerimento = \miti\Tratamento::requerer('arquivo/Miti.js');
		$this->assertSame(1, preg_match($padrao, $requerimento));
	}
	
	public function testRequererCss(){
		$padrao = "/^<link rel='stylesheet' href='arquivo\/miti\.css\?hash=[a-f\d]{32}' \/>\\n$/i";
		$requerimento = \miti\Tratamento::requerer('arquivo/miti.css');
		$this->assertSame(1, preg_match($padrao, $requerimento));
	}
	
	public function testNaoSubstituir(){
		$this->assertSame('a', \miti\Tratamento::substituir('a', 'b', 'c'));
	}
	
	public function testSubstituir(){
		$this->assertSame('c', \miti\Tratamento::substituir('a', 'a', 'c'));
	}
	
	public function testIndexar(){
		$vetor = array();
		$vetor = \miti\Tratamento::indexar($vetor, array('teste'));
		$this->assertTrue(isset($vetor['teste']));
	}
	
	public function testArquivar(){
		$esperado = file_get_contents(CFG_RAIZ.'/unit/arquivo/miti.txt');
		$arquivo = \miti\Tratamento::arquivar('', CFG_RAIZ.'/unit/arquivo/miti.txt');
		$this->assertSame($esperado, $arquivo);
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
		$this->assertSame(null, \miti\Tratamento::encurtar(''));
	}
	
	public function testEncurtarArray(){
		$esperados = array('aaaaa...', 'bbbbb...', 'ccccc...');
		$curtos = \miti\Tratamento::encurtar(array('aaaaaaaaaa', 'bbbbbbbbbb', 'cccccccccc'));
		$this->assertSame($esperados, $curtos);
	}
	
	public function testEncurtarScalar(){
		$this->assertSame('aaaaa...', \miti\Tratamento::encurtar('aaaaaaaaaa'));
	}
	
	public function testEnxugarVazio(){
		$this->assertSame(null, \miti\Tratamento::enxugar(''));
	}
	
	public function testEnxugarArray(){
		$esperados = array('oaaac_', 'eeoouc_', 'aaaeie_', 'iooou');
		$enxutos = \miti\Tratamento::enxugar(array('ôàáãÇ ', 'éêóõúç ', 'ÀÁÃÉíÊ ', 'ÍÓÔÕÚ'));
		$this->assertSame($esperados, $enxutos);
	}
	
	public function testEnxugarScalar(){
		$esperado = 'oaaac_eeoouc_aaaeie_iooou';
		$enxuto = \miti\Tratamento::enxugar('ôàáãÇ éêóõúç ÀÁÃÉíÊ ÍÓÔÕÚ');
		$this->assertSame($esperado, $enxuto);
	}
}
