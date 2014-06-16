<?php
class MitiPaginacaoTest extends PHPUnit_Framework_TestCase{
	public function testGetInicio(){
		$MitiPaginacao=new MitiPaginacao(100,15,5,10);
		$this->assertSame(60,$MitiPaginacao->getInicio());
	}
	
	public function testCriarComNenhumRegistro(){
		$MitiPaginacao=new MitiPaginacao(0,1,1,3);
		
		$this->assertSame(
			'Não há registros para esta busca',
			$MitiPaginacao->criar('?pagina=','off','on')
		);
	}
	
	public function testCriarComPoucosRegistros(){
		$MitiPaginacao=new MitiPaginacao(1,1,1,1);
		
		$this->assertSame(
			'<span class="off">Primeira</span>'
			.'<span class="off">Anterior</span>'
			.'<span class="on">1</span>'
			.'<span class="off">Próxima</span>'
			.'<span class="off">Última</span>',
		
			$MitiPaginacao->criar('?pagina=','off','on')
		);
	}
	
	public function testCriarComMuitosRegistros(){
		$MitiPaginacao=new MitiPaginacao(100,10,2,5);
		
		$this->assertSame(
			'<a href="?pagina=1">Primeira</a>'
			.'<a href="?pagina=1">Anterior</a>'
			.'<a href="?pagina=1">1</a>'
			.'<span class="on">2</span>'
			.'<a href="?pagina=3">3</a>'
			.'<a href="?pagina=4">4</a>'
			.'<a href="?pagina=3">Próxima</a>'
			.'<a href="?pagina=10">Última</a>',
		
			$MitiPaginacao->criar('?pagina=','off','on')
		);
	}
}
