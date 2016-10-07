<?php
class PaginacaoTest extends PHPUnit_Framework_TestCase{
	public function testGetInicio(){
		$Paginacao = new \Miti\Paginacao(100, 15, 5, 10);
		$this->assertSame(60, $Paginacao->getInicio());
	}
	
	public function testCriarComPoucosRegistros(){
		$Paginacao = new \Miti\Paginacao(1, 1, 1, 1);
		
		$html =
			"<span class='off'>&#8634;</span>"
			."<span class='off'>&#8592;</span>"
			."<span class='on'>1</span>"
			."<span class='off'>&#8594;</span>"
			."<span class='off'>&#8635;</span>"
		;
		
		$this->assertSame($html, $Paginacao->criar('pagina', 'on', 'off'));
	}
	
	public function testCriarComMuitosRegistros(){
		$Paginacao = new \Miti\Paginacao(100, 10, 2, 5);
		
		$html =
			"<a href='?a=b&amp;pagina=1'>&#8634;</a>"
			."<a href='?a=b&amp;pagina=1'>&#8592;</a>"
			."<a href='?a=b&amp;pagina=1'>1</a>"
			."<span class='on'>2</span>"
			."<a href='?a=b&amp;pagina=3'>3</a>"
			."<a href='?a=b&amp;pagina=4'>4</a>"
			."<a href='?a=b&amp;pagina=3'>&#8594;</a>"
			."<a href='?a=b&amp;pagina=10'>&#8635;</a>"
		;
		
		$this->assertSame($html, $Paginacao->criar('pagina', 'on', 'off', array('a' => 'b')));
	}
}
