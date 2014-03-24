<?php
class MitiTratamentoTest extends PHPUnit_Framework_TestCase{
	protected $MitiTratamento;
	
	protected function setUp(){
		$this->MitiTratamento=new MitiTratamento;
	}
	
	public function testHtmlSpecialChars(){
		$this->assertSame(null,$this->MitiTratamento->htmlSpecialChars(''));
	}
	
	public function testHtmlSpecialCharsArray(){
		$teste=$this->MitiTratamento->htmlSpecialChars(array("'",'"','&','<','>'));
		$this->assertSame(array('&#039;','&quot;','&amp;','&lt;','&gt;'),$teste);
	}
	
	public function testHtmlSpecialCharsScalar(){
		$teste=$this->MitiTratamento->htmlSpecialChars('\'"&<>');
		$this->assertSame('&#039;&quot;&amp;&lt;&gt;',$teste);
	}
	
	public function testEncurtar(){
		$this->assertSame(null,$this->MitiTratamento->encurtar(''));
	}
	
	public function testEncurtarArray(){
		$teste=$this->MitiTratamento->encurtar(array('aaaaaaaaaa','bbbbbbbbbb','cccccccccc'));
		$this->assertSame(array('aaaaa...','bbbbb...','ccccc...'),$teste);
	}
	
	public function testEncurtarScalar(){
		$teste=$this->MitiTratamento->encurtar('aaaaaaaaaa');
		$this->assertSame('aaaaa...',$teste);
	}
	
	public function testRemoverAcentos(){
		$this->assertSame(null,$this->MitiTratamento->removerAcentos(''));
	}
	
	public function testRemoverAcentosArray(){
		$teste=$this->MitiTratamento->removerAcentos(array('á','È','î','Õ','ü','Ç'));
		$this->assertSame(array('a','E','i','O','u','C'),$teste);
	}
	
	public function testRemoverAcentosScalar(){
		$teste=$this->MitiTratamento->removerAcentos('ç');
		$this->assertSame('c',$teste);
	}
}