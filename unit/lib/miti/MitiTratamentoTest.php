<?php
class MitiTratamentoTest extends PHPUnit_Framework_TestCase{
	protected $MitiTratamento;
	
	protected function setUp(){
		$this->MitiTratamento=new MitiTratamento;
	}
	
	public function testGarantirValor(){
		$this->assertSame('a',$this->MitiTratamento->garantirValor('','a'));
		$this->assertSame('b',$this->MitiTratamento->garantirValor('b','a'));
	}
	
	public function testHtmlSpecialChars(){
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
	
	public function testEncurtar(){
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
	
	public function testRemoverAcentos(){
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
