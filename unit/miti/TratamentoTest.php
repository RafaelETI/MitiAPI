<?php
class TratamentoTest extends PHPUnit_Framework_TestCase{
	public function testRequererJs(){
		$afirmacao=
			"<script src='"
			.RAIZ.'/unit/arquivo/Miti.js?hash=9c8dae732b54244ad5d731c9b0ffda22'
			."'></script>\n"
		;
		
		$this->assertSame($afirmacao,\miti\Tratamento::requerer(RAIZ.'/unit/arquivo/Miti.js'));
	}
	
	public function testRequererCss(){
		$afirmacao=
			"<link rel='stylesheet' href='"
			.RAIZ.'/unit/arquivo/miti.css?hash=0460587e6b979a3273027e4f079219dd'
			."' />\n"
		;
		
		$this->assertSame($afirmacao,\miti\Tratamento::requerer(RAIZ.'/unit/arquivo/miti.css'));
	}
	
	public function testNaoSubstituirValor(){
		$this->assertSame('a',\miti\Tratamento::substituirValor('a','b','c'));
	}
	
	public function testSubstituirValor(){
		$this->assertSame('c',\miti\Tratamento::substituirValor('a','a','c'));
	}
	
	public function testGarantirIndices(){
		$_POST=\miti\Tratamento::garantirIndices($_POST,array('teste'));
		$this->assertTrue(isset($_POST['teste']));
	}
	
	public function testGarantirArquivo(){
		$esperado=file_get_contents(RAIZ.'/unit/arquivo/miti.txt');
		$arquivo=\miti\Tratamento::garantirArquivo('',RAIZ.'/unit/arquivo/miti.txt');
		$this->assertSame($esperado,$arquivo);
	}
	
	public function testHtmlSpecialCharsVazio(){
		$this->assertSame(null,\miti\Tratamento::htmlSpecialChars(''));
	}
	
	public function testHtmlSpecialCharsArray(){
		$esperado=array('&#039;','&quot;','&amp;','&lt;','&gt;');
		$escapados=\miti\Tratamento::htmlSpecialChars(array("'",'"','&','<','>'));
		$this->assertSame($esperado,$escapados);
	}
	
	public function testHtmlSpecialCharsScalar(){
		$esperado='&#039;&quot;&amp;&lt;&gt;';
		$this->assertSame($esperado,\miti\Tratamento::htmlSpecialChars('\'"&<>'));
	}
	
	public function testEncurtarVazio(){
		$this->assertSame(null,\miti\Tratamento::encurtar(''));
	}
	
	public function testEncurtarArray(){
		$esperado=array('aaaaa...','bbbbb...','ccccc...');
		$curtos=\miti\Tratamento::encurtar(array('aaaaaaaaaa','bbbbbbbbbb','cccccccccc'));
		$this->assertSame($esperado,$curtos);
	}
	
	public function testEncurtarScalar(){
		$this->assertSame('aaaaa...',\miti\Tratamento::encurtar('aaaaaaaaaa'));
	}
}
