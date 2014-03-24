<?php
class MitiPaginacaoTest extends PHPUnit_Framework_TestCase{
	public function testCriarComPoucosRegistros(){
		$MitiPaginacao=new MitiPaginacao(1,1,1);
		$this->assertSame(1,$MitiPaginacao->getNumReg());
		$this->assertSame(0,$MitiPaginacao->getInicio());
		
		$MitiPaginacao->setTotal(1);
		
		$afirmacao='<span class="off">Primeira</span>';
		$afirmacao.='<span class="off">Anterior</span>';
		$afirmacao.='<span class="on">1</span>';
		$afirmacao.='<span class="off">Próxima</span>';
		$afirmacao.='<span class="off">Última</span>';
		
		$this->assertSame($afirmacao,$MitiPaginacao->criar('?pg=','off','on'));
	}
	
	public function testCriarComMuitosRegistros(){
		$MitiPaginacao=new MitiPaginacao(10,2,5);
		$this->assertSame(10,$MitiPaginacao->getNumReg());
		$this->assertSame(10,$MitiPaginacao->getInicio());
		
		$MitiPaginacao->setTotal(100);
		
		$afirmacao='<a href="?pg=1">Primeira</a>';
		$afirmacao.='<a href="?pg=1">Anterior</a>';
		$afirmacao.='<a href="?pg=1">1</a>';
		$afirmacao.='<span class="on">2</span>';
		$afirmacao.='<a href="?pg=3">3</a>';
		$afirmacao.='<a href="?pg=4">4</a>';
		$afirmacao.='<a href="?pg=3">Próxima</a>';
		$afirmacao.='<a href="?pg=10">Última</a>';
		
		$this->assertSame($afirmacao,$MitiPaginacao->criar('?pg=','off','on'));
	}
}