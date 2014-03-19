<?php
class MitiPaginacaoTest extends PHPUnit_Framework_TestCase{
	protected $MitiPaginacao;
	
	protected function setUp(){
		require_once 'Config.php';
		Config::setInstance();
		
		$this->MitiPaginacao=new MitiPaginacao(1,1,1);
	}
	
	public function testSetTotal(){
		$this->MitiPaginacao->setTotal(1);
	}
	
	public function testGetNumReg(){
		$this->assertSame(1,$this->MitiPaginacao->getNumReg());
	}
	
	public function testGetInicio(){
		$this->assertSame(0,$this->MitiPaginacao->getInicio());
	}
	
	public function testCriar(){
		$this->testSetTotal();
		
		$afirmacao='<span class="off">Primeira</span>';
		$afirmacao.='<span class="off">Anterior</span>';
		$afirmacao.='<span class="on">1</span>';
		$afirmacao.='<span class="off">Próxima</span>';
		$afirmacao.='<span class="off">Última</span>';
		
		$this->assertSame($afirmacao,$this->MitiPaginacao->criar('?pg=','off','on'));
		
		$this->criarComMuitosRegistros();
	}
	
	private function criarComMuitosRegistros(){
		$MitiPaginacao=new MitiPaginacao(10,2,5);
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