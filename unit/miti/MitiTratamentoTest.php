<?php
class MitiTratamentoTest extends PHPUnit_Framework_TestCase{
	private $MitiTratamento;
	
	protected function setUp(){
		$this->MitiTratamento=new MitiTratamento;
	}
	
	public function testRequererJs(){
		$afirmacao=
			"<script src='"
			.RAIZ.'unit/arquivo/Miti.js?hash=9c8dae732b54244ad5d731c9b0ffda22'
			."'></script>\n"
		;
		
		$this->assertSame(
			$afirmacao,$this->MitiTratamento->requerer(RAIZ.'unit/arquivo/Miti.js')
		);
	}
	
	public function testRequererCss(){
		$afirmacao=
			"<link rel='stylesheet' type='text/css' href='"
			.RAIZ.'unit/arquivo/miti.css?hash=431f86b7bbc84cfeaa7b66544c32dc0c'
			."' />\n"
		;
		
		$this->assertSame(
			$afirmacao,$this->MitiTratamento->requerer(RAIZ.'unit/arquivo/miti.css')
		);
	}
	
	public function testNaoSubstituirValor(){
		$this->assertSame('a',$this->MitiTratamento->substituirValor('a','b','c'));
	}
	
	public function testSubstituirValor(){
		$this->assertSame('c',$this->MitiTratamento->substituirValor('a','a','c'));
	}
	
	public function testGarantirIndices(){
		$_POST=$this->MitiTratamento->garantirIndices($_POST,array('teste'));
		$this->assertTrue(isset($_POST['teste']));
	}
	
	public function testGarantirArquivo(){
		$this->assertSame(
			file_get_contents(RAIZ.'unit/arquivo/miti.txt'),
			$this->MitiTratamento->garantirArquivo('',RAIZ.'unit/arquivo/miti.txt')
		);
	}
	
	public function testHtmlSpecialCharsVazio(){
		$this->assertSame(null,$this->MitiTratamento->htmlSpecialChars(''));
	}
	
	public function testHtmlSpecialCharsArray(){
		$this->assertSame(
			array('&#039;','&quot;','&amp;','&lt;','&gt;'),
			$this->MitiTratamento->htmlSpecialChars(array("'",'"','&','<','>'))
		);
	}
	
	public function testHtmlSpecialCharsScalar(){
		$this->assertSame(
			'&#039;&quot;&amp;&lt;&gt;',
			$this->MitiTratamento->htmlSpecialChars('\'"&<>')
		);
	}
	
	public function testEncurtarVazio(){
		$this->assertSame(null,$this->MitiTratamento->encurtar(''));
	}
	
	public function testEncurtarArray(){
		$this->assertSame(
			array('aaaaa...','bbbbb...','ccccc...'),
		
			$this->MitiTratamento->encurtar(
				array('aaaaaaaaaa','bbbbbbbbbb','cccccccccc')
			)
		);
	}
	
	public function testEncurtarScalar(){
		$this->assertSame(
			'aaaaa...',$this->MitiTratamento->encurtar('aaaaaaaaaa')
		);
	}
	
	public function testRemoverAcentosVazio(){
		$this->assertSame(null,$this->MitiTratamento->removerAcentos(''));
	}
	
	public function testRemoverAcentosArray(){
		$this->assertSame(
			array('a','E','i','O','u','C'),
			$this->MitiTratamento->removerAcentos(array('á','È','î','Õ','ü','Ç'))
		);
	}
	
	public function testRemoverAcentosScalar(){
		$this->assertSame('c',$this->MitiTratamento->removerAcentos('ç'));
	}
}
