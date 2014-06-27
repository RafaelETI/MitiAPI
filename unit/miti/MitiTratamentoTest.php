<?php
class MitiTratamentoTest extends PHPUnit_Framework_TestCase{
	public function testRequererJs(){
		$afirmacao=
			"<script src='"
			.RAIZ.'unit/arquivo/Miti.js?hash=0db55359e993e67605af76bd1ef26897'
			."'></script>\n"
		;
		
		$this->assertSame(
			$afirmacao,MitiTratamento::requerer(RAIZ.'unit/arquivo/Miti.js')
		);
	}
	
	public function testRequererCss(){
		$afirmacao=
			"<link rel='stylesheet' type='text/css' href='"
			.RAIZ.'unit/arquivo/miti.css?hash=b57481f4772a0c3821087160def99e5e'
			."' />\n"
		;
		
		$this->assertSame(
			$afirmacao,MitiTratamento::requerer(RAIZ.'unit/arquivo/miti.css')
		);
	}
	
	public function testNaoSubstituirValor(){
		$this->assertSame('a',MitiTratamento::substituirValor('a','b','c'));
	}
	
	public function testSubstituirValor(){
		$this->assertSame('c',MitiTratamento::substituirValor('a','a','c'));
	}
	
	public function testGarantirIndices(){
		$_POST=MitiTratamento::garantirIndices($_POST,array('teste'));
		$this->assertTrue(isset($_POST['teste']));
	}
	
	public function testGarantirArquivo(){
		$this->assertSame(
			file_get_contents(RAIZ.'unit/arquivo/miti.txt'),
			MitiTratamento::garantirArquivo('',RAIZ.'unit/arquivo/miti.txt')
		);
	}
	
	public function testHtmlSpecialCharsVazio(){
		$this->assertSame(null,MitiTratamento::htmlSpecialChars(''));
	}
	
	public function testHtmlSpecialCharsArray(){
		$this->assertSame(
			array('&#039;','&quot;','&amp;','&lt;','&gt;'),
			MitiTratamento::htmlSpecialChars(array("'",'"','&','<','>'))
		);
	}
	
	public function testHtmlSpecialCharsScalar(){
		$this->assertSame(
			'&#039;&quot;&amp;&lt;&gt;',
			MitiTratamento::htmlSpecialChars('\'"&<>')
		);
	}
	
	public function testEncurtarVazio(){
		$this->assertSame(null,MitiTratamento::encurtar(''));
	}
	
	public function testEncurtarArray(){
		$this->assertSame(
			array('aaaaa...','bbbbb...','ccccc...'),
			MitiTratamento::encurtar(array('aaaaaaaaaa','bbbbbbbbbb','cccccccccc'))
		);
	}
	
	public function testEncurtarScalar(){
		$this->assertSame('aaaaa...',MitiTratamento::encurtar('aaaaaaaaaa'));
	}
	
	public function testRemoverAcentosVazio(){
		$this->assertSame(null,MitiTratamento::removerAcentos(''));
	}
	
	public function testRemoverAcentosArray(){
		$this->assertSame(
			array('a','E','i','O','u','C'),
			MitiTratamento::removerAcentos(array('á','È','î','Õ','ü','Ç'))
		);
	}
	
	public function testRemoverAcentosScalar(){
		$this->assertSame('c',MitiTratamento::removerAcentos('ç'));
	}
}
