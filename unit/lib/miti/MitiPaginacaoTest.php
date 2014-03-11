<?php
require_once 'Config.php'; new Config;

class MitiPaginacaoTest extends PHPUnit_Framework_TestCase{
	protected $MitiPaginacao;
	
	protected function setUp(){
		$this->MitiPaginacao=new MitiPaginacao(10,2,3);
	}
	
	public function testSetTotal(){
		$this->MitiPaginacao->setTotal(100);
	}
	
	public function testCriar(){
		$this->testSetTotal();
		
		$afirmacao='<a href="?pg=1">Primeira</a>';
		$afirmacao.='<a href="?pg=1">Anterior</a>';
		$afirmacao.='<a href="?pg=1">1</a>';
		$afirmacao.='<span class="on">2</span>';
		$afirmacao.='<a href="?pg=3">3</a>';
		$afirmacao.='<a href="?pg=3">Próxima</a>';
		$afirmacao.='<a href="?pg=10">Última</a>';
		
		$this->assertSame($afirmacao,$this->MitiPaginacao->criar('?pg=','off','on'));
	}
}