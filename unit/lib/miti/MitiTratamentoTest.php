<?php
class MitiTratamentoTest extends PHPUnit_Framework_TestCase{
	protected $MitiTratamento;
	
	protected function setUp(){
		require_once 'Config.php';
		Config::setInstance();
		
		$this->MitiTratamento=new MitiTratamento;
	}
	
	public function testHtmlSpecialChars(){
		$this->assertSame(null,$this->MitiTratamento->htmlSpecialChars(''));
		
		$this->htmlSpecialCharsArray();
		$this->htmlSpecialCharsScalar();
	}
	
	private function htmlSpecialCharsArray(){
		$teste=$this->MitiTratamento->htmlSpecialChars(array("'",'"','&','<','>'));
		$this->assertSame(array('&#039;','&quot;','&amp;','&lt;','&gt;'),$teste);
	}
	
	private function htmlSpecialCharsScalar(){
		$teste=$this->MitiTratamento->htmlSpecialChars('\'"&<>');
		$this->assertSame('&#039;&quot;&amp;&lt;&gt;',$teste);
	}
	
	public function testEncurtar(){
		$this->assertSame(null,$this->MitiTratamento->encurtar(''));
		
		$this->encurtarArray();
		$this->encurtarScalar();
	}
	
	private function encurtarArray(){
		$teste=$this->MitiTratamento->encurtar(array('aaaaaaaaaa','bbbbbbbbbb','cccccccccc'));
		$this->assertSame(array('aaaaa...','bbbbb...','ccccc...'),$teste);
	}
	
	private function encurtarScalar(){
		$teste=$this->MitiTratamento->encurtar('aaaaaaaaaa');
		$this->assertSame('aaaaa...',$teste);
	}
	
	public function testRemoverAcentos(){
		$this->assertSame(null,$this->MitiTratamento->removerAcentos(''));
		
		$this->removerAcentosArray();
		$this->removerAcentosScalar();
	}
	
	private function removerAcentosArray(){
		$teste=$this->MitiTratamento->removerAcentos(array('á','È','î','Õ','ü','Ç'));
		$this->assertSame(array('a','E','i','O','u','C'),$teste);
	}
	
	private function removerAcentosScalar(){
		$teste=$this->MitiTratamento->removerAcentos('ç');
		$this->assertSame('c',$teste);
	}
}