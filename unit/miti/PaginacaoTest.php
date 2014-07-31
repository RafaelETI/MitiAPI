<?php
class PaginacaoTest extends PHPUnit_Framework_TestCase{
	public function testGetInicio(){
		$Paginacao=new \miti\Paginacao(100,15,5,10);
		$this->assertSame(60,$Paginacao->getInicio());
	}
	
	public function testCriarComNenhumRegistro(){
		$Paginacao=new \miti\Paginacao(0,1,1,3);
		
		$mensagem='Não há registros para esta busca';
		$this->assertSame($mensagem,$Paginacao->criar('?pagina=','on','off'));
	}
	
	public function testCriarComPoucosRegistros(){
		$Paginacao=new \miti\Paginacao(1,1,1,1);
		
		$html='<span class="off">Primeira</span>'
			.'<span class="off">Anterior</span>'
			.'<span class="on">1</span>'
			.'<span class="off">Próxima</span>'
			.'<span class="off">Última</span>'
		;
		
		$this->assertSame($html,$Paginacao->criar('?pagina=','on','off'));
	}
	
	public function testCriarComMuitosRegistros(){
		$Paginacao=new \miti\Paginacao(100,10,2,5);
		
		$html='<a href="?pagina=1">Primeira</a>'
			.'<a href="?pagina=1">Anterior</a>'
			.'<a href="?pagina=1">1</a>'
			.'<span class="on">2</span>'
			.'<a href="?pagina=3">3</a>'
			.'<a href="?pagina=4">4</a>'
			.'<a href="?pagina=3">Próxima</a>'
			.'<a href="?pagina=10">Última</a>'
		;
		
		$this->assertSame($html,$Paginacao->criar('?pagina=','on','off'));
	}
}
