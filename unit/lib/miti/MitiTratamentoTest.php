<?php
class MitiTratamentoTest extends PHPUnit_Framework_TestCase{
	private $MitiTratamento;
	
	protected function setUp(){
		$this->MitiTratamento=new MitiTratamento;
	}
	
	public function testGarantirValorVazio(){
		$this->assertSame('a',$this->MitiTratamento->garantirValor('','a'));
	}
	
	public function testGarantirValor(){
		$this->assertSame('b',$this->MitiTratamento->garantirValor('b','a'));
	}
	
	public function testGarantirArquivo(){
		$this->assertSame(
			file_get_contents(RAIZ.'msc/mitiunit.txt'),
			$this->MitiTratamento->garantirArquivo('',RAIZ.'msc/mitiunit.txt')
		);
	}
	
	public function testGarantirPost(){
		$this->MitiTratamento->garantirPost(array('teste'));
		$this->assertTrue(isset($_POST['teste']));
	}
	
	public function testGarantirGet(){
		$this->MitiTratamento->garantirGet('teste',true);
		$this->assertSame(true,$_GET['teste']);
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
			$this->MitiTratamento->removerAcentos(array('�','�','�','�','�','�'))
		);
	}
	
	public function testRemoverAcentosScalar(){
		$this->assertSame('c',$this->MitiTratamento->removerAcentos('�'));
	}
}
