<?php
class MitiTabelaTest extends PHPUnit_Framework_TestCase{
	protected $MitiTabela;
	
	protected function setUp(){
		$this->MitiTabela=new MitiTabela('categoria');
	}
	
	public function testGetNome(){
		$this->assertSame('categoria',$this->MitiTabela->getNome());
	}
	
	public function testGetTipos(){
		$this->assertSame(
			array('id'=>'float','nome'=>'string','status'=>'string'),
			$this->MitiTabela->getTipos()
		);
	}
	
	public function testGetAnulaveis(){
		$this->assertSame(
			array('id'=>false,'nome'=>false,'status'=>true),
			$this->MitiTabela->getAnulaveis()
		);
	}
	
	public function testGetTamanhos(){
		$this->assertSame(
			array('id'=>3,'nome'=>30,'status'=>1),
			$this->MitiTabela->getTamanhos()
		);
	}
	
	public function testGetPkCampo(){
		$this->assertSame('id',$this->MitiTabela->getPkCampo());
	}
	
	public function testGetPkTipo(){
		$this->assertSame('float',$this->MitiTabela->getPkTipo());
	}
}
