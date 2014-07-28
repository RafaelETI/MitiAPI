<?php
class TratamentoTest extends PHPUnit_Framework_TestCase{
	public function testRequererJs(){
		$afirmacao=
			"<script src='"
			.RAIZ.'/unit/arquivo/Miti.js?hash=9c8dae732b54244ad5d731c9b0ffda22'
			."'></script>\n"
		;
		
		$this->assertSame($afirmacao,Miti\Tratamento::requerer(RAIZ.'/unit/arquivo/Miti.js'));
	}
	
	public function testRequererCss(){
		$afirmacao=
			"<link rel='stylesheet' type='text/css' href='"
			.RAIZ.'/unit/arquivo/miti.css?hash=0460587e6b979a3273027e4f079219dd'
			."' />\n"
		;
		
		$this->assertSame($afirmacao,Miti\Tratamento::requerer(RAIZ.'/unit/arquivo/miti.css'));
	}
	
	public function testNaoSubstituirValor(){
		$this->assertSame('a',Miti\Tratamento::substituirValor('a','b','c'));
	}
	
	public function testSubstituirValor(){
		$this->assertSame('c',Miti\Tratamento::substituirValor('a','a','c'));
	}
	
	public function testGarantirIndices(){
		$_POST=Miti\Tratamento::garantirIndices($_POST,array('teste'));
		$this->assertTrue(isset($_POST['teste']));
	}
	
	public function testGarantirArquivo(){
		$this->assertSame(
			file_get_contents(RAIZ.'/unit/arquivo/miti.txt'),
			Miti\Tratamento::garantirArquivo('',RAIZ.'/unit/arquivo/miti.txt')
		);
	}
	
	public function testHtmlSpecialCharsVazio(){
		$this->assertSame(null,Miti\Tratamento::htmlSpecialChars(''));
	}
	
	public function testHtmlSpecialCharsArray(){
		$this->assertSame(
			array('&#039;','&quot;','&amp;','&lt;','&gt;'),
			Miti\Tratamento::htmlSpecialChars(array("'",'"','&','<','>'))
		);
	}
	
	public function testHtmlSpecialCharsScalar(){
		$especiais='&#039;&quot;&amp;&lt;&gt;';
		$this->assertSame($especiais,Miti\Tratamento::htmlSpecialChars('\'"&<>'));
	}
	
	public function testEncurtarVazio(){
		$this->assertSame(null,Miti\Tratamento::encurtar(''));
	}
	
	public function testEncurtarArray(){
		$this->assertSame(
			array('aaaaa...','bbbbb...','ccccc...'),
			Miti\Tratamento::encurtar(array('aaaaaaaaaa','bbbbbbbbbb','cccccccccc'))
		);
	}
	
	public function testEncurtarScalar(){
		$this->assertSame('aaaaa...',Miti\Tratamento::encurtar('aaaaaaaaaa'));
	}
	
	public function testRemoverAcentosVazio(){
		$this->assertSame(null,Miti\Tratamento::removerAcentos(''));
	}
	
	public function testRemoverAcentosArray(){
		$this->assertSame(
			array('a','E','i','O','u','C'),
			Miti\Tratamento::removerAcentos(array('á','È','î','Õ','ü','Ç'))
		);
	}
	
	public function testRemoverAcentosScalar(){
		$this->assertSame('c',Miti\Tratamento::removerAcentos('ç'));
	}
}
