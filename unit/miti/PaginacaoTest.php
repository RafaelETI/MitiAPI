<?php
class PaginacaoTest extends PHPUnit_Framework_TestCase{
	public function testGetInicio(){
		$Paginacao=new \miti\Paginacao(100,15,5,10);
		$this->assertSame(60,$Paginacao->getInicio());
	}
	
	public function testCriarComPoucosRegistros(){
		$Paginacao=new \miti\Paginacao(1,1,1,1);
		
		$html=
			"<span class='off'>&laquo;</span>"
			."<span class='off'>&lsaquo;</span>"
			."<span class='on'>1</span>"
			."<span class='off'>&rsaquo;</span>"
			."<span class='off'>&raquo;</span>"
		;
		
		$this->assertSame($html,$Paginacao->criar('pagina','on','off'));
	}
	
	public function testCriarComMuitosRegistros(){
		$Paginacao=new \miti\Paginacao(100,10,2,5);
		
		$html=
			"<a href='?pagina=1'>&laquo;</a>"
			."<a href='?pagina=1'>&lsaquo;</a>"
			."<a href='?pagina=1'>1</a>"
			."<span class='on'>2</span>"
			."<a href='?pagina=3'>3</a>"
			."<a href='?pagina=4'>4</a>"
			."<a href='?pagina=3'>&rsaquo;</a>"
			."<a href='?pagina=10'>&raquo;</a>"
		;
		
		$this->assertSame($html,$Paginacao->criar('pagina','on','off'));
	}
}
