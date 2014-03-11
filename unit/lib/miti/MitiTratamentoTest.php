<?php
require_once 'Config.php'; new Config;

class MitiTratamentoTest extends PHPUnit_Framework_TestCase{
	protected $MitiTratamento;
	
	protected function setUp(){
		$this->MitiTratamento=new MitiTratamento;
	}
	
	public function testHtmlSpecialChars(){
		$this->htmlSpecialCharsArray();
		$this->htmlSpecialCharsScalar();
	}
	
	private function htmlSpecialCharsArray(){
		$teste=array("'",'"','&','<','>');
		$this->MitiTratamento->htmlSpecialChars($teste);
		$this->assertSame(array('&#039;','&quot;','&amp;','&lt;','&gt;'),$teste);
	}
	
	private function htmlSpecialCharsScalar(){
		$teste='\'"&<>';
		$this->MitiTratamento->htmlSpecialChars($teste);
		$this->assertSame('&#039;&quot;&amp;&lt;&gt;',$teste);
	}
	
	public function testEncurtar(){
		$this->encurtarArray();
		$this->encurtarScalar();
	}
	
	private function encurtarArray(){
		$teste=array('aaaaaaaaaa','bbbbbbbbbb','cccccccccc');
		$this->MitiTratamento->encurtar($teste);
		$this->assertSame(array('aaaaa...','bbbbb...','ccccc...'),$teste);
	}
	
	private function encurtarScalar(){
		$teste='aaaaaaaaaa';
		$this->MitiTratamento->encurtar($teste);
		$this->assertSame('aaaaa...',$teste);
	}
	
	public function testRemoverAcentos(){
		$this->removerAcentosArray();
		$this->removerAcentosScalar();
	}
	
	private function removerAcentosArray(){
		$teste=array('á','È','î','Õ','ü','Ç');
		$this->MitiTratamento->removerAcentos($teste);
		$this->assertSame(array('a','E','i','O','u','C'),$teste);
	}
	
	private function removerAcentosScalar(){
		$teste='ç';
		$this->MitiTratamento->removerAcentos($teste);
		$this->assertSame('c',$teste);
	}
}