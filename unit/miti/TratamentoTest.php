<?php
class TratamentoTest extends PHPUnit_Framework_TestCase{
	public function testRequererJs(){
		$afirmacao=
			"<script src='"
			.CFG_RAIZ.'/unit/arquivo/Miti.js?hash=44cac76ce9712f378cd2ce873c867d35'
			."'></script>\n"
		;
		
		$this->assertSame($afirmacao,\miti\Tratamento::requerer(CFG_RAIZ.'/unit/arquivo/Miti.js'));
	}
	
	public function testRequererCss(){
		$afirmacao=
			"<link rel='stylesheet' href='"
			.CFG_RAIZ.'/unit/arquivo/miti.css?hash=e631c0fcfe2d0908386964fea8f43003'
			."' />\n"
		;
		
		$this->assertSame($afirmacao,\miti\Tratamento::requerer(CFG_RAIZ.'/unit/arquivo/miti.css'));
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
