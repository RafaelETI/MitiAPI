<?php
require_once 'Config.php'; Config::setInstance();

class MitiPaginacaoTest extends PHPUnit_Framework_TestCase{
	protected $MitiPaginacao;
	
	protected function setUp(){
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
	}
}