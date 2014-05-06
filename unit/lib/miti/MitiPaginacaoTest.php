<?php
class MitiPaginacaoTest extends PHPUnit_Framework_TestCase{
	public function testCriarComNenhumRegistro(){
		$MitiPaginacao=new MitiPaginacao(1,1,1);
		$this->assertSame(1,$MitiPaginacao->getNumReg());
		$this->assertSame(0,$MitiPaginacao->getInicio());
		
		$MitiPaginacao->setTotal(0);
		
		$this->assertSame(
			'Não há registros para esta busca',
			$MitiPaginacao->criar('?pg=','off','on')
		);
	}
	
	public function testCriarComPoucosRegistros(){
		$MitiPaginacao=new MitiPaginacao(1,1,1);
		$this->assertSame(1,$MitiPaginacao->getNumReg());
		$this->assertSame(0,$MitiPaginacao->getInicio());
		
		$MitiPaginacao->setTotal(1);
		
		$this->assertSame(
			'<span class="off">Primeira</span>'
			.'<span class="off">Anterior</span>'
			.'<span class="on">1</span>'
			.'<span class="off">Próxima</span>'
			.'<span class="off">Última</span>',
		
			$MitiPaginacao->criar('?pg=','off','on')
		);
	}
	
	public function testCriarComMuitosRegistros(){
		$MitiPaginacao=new MitiPaginacao(10,2,5);
		$this->assertSame(10,$MitiPaginacao->getNumReg());
		$this->assertSame(10,$MitiPaginacao->getInicio());
		
		$MitiPaginacao->setTotal(100);
		
		$this->assertSame(
			'<a href="?pg=1">Primeira</a>'
			.'<a href="?pg=1">Anterior</a>'
			.'<a href="?pg=1">1</a>'
			.'<span class="on">2</span>'
			.'<a href="?pg=3">3</a>'
			.'<a href="?pg=4">4</a>'
			.'<a href="?pg=3">Próxima</a>'
			.'<a href="?pg=10">Última</a>',
		
			$MitiPaginacao->criar('?pg=','off','on')
		);
	}
}
