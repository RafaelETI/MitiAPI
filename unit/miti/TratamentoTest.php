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
	
	public function testNaoSubstituirValor(){
		$this->assertSame('a',\miti\Tratamento::substituirValor('a','b','c'));
	}
	
	public function testSubstituirValor(){
		$this->assertSame('c',\miti\Tratamento::substituirValor('a','a','c'));
	}
	
	public function testGarantirIndices(){
		$vetor=array();
		$vetor=\miti\Tratamento::garantirIndices($vetor,array('teste'));
		$this->assertTrue(isset($vetor['teste']));
	}
	
	public function testGarantirArquivo(){
		$esperado=file_get_contents(CFG_RAIZ.'/unit/arquivo/miti.txt');
		$arquivo=\miti\Tratamento::garantirArquivo('',CFG_RAIZ.'/unit/arquivo/miti.txt');
		$this->assertSame($esperado,$arquivo);
	}
	
	public function testHtmlSpecialCharsVazio(){
		$this->assertSame(null,\miti\Tratamento::htmlSpecialChars(''));
	}
	
	public function testHtmlSpecialCharsArray(){
		$esperados=array('&#039;','&quot;','&amp;','&lt;','&gt;');
		$escapados=\miti\Tratamento::htmlSpecialChars(array("'",'"','&','<','>'));
		$this->assertSame($esperados,$escapados);
	}
	
	public function testHtmlSpecialCharsScalar(){
		$esperado='&#039;&quot;&amp;&lt;&gt;';
		$this->assertSame($esperado,\miti\Tratamento::htmlSpecialChars('\'"&<>'));
	}
	
	public function testEncurtarVazio(){
		$this->assertSame(null,\miti\Tratamento::encurtar(''));
	}
	
	public function testEncurtarArray(){
		$esperados=array('aaaaa...','bbbbb...','ccccc...');
		$curtos=\miti\Tratamento::encurtar(array('aaaaaaaaaa','bbbbbbbbbb','cccccccccc'));
		$this->assertSame($esperados,$curtos);
	}
	
	public function testEncurtarScalar(){
		$this->assertSame('aaaaa...',\miti\Tratamento::encurtar('aaaaaaaaaa'));
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
