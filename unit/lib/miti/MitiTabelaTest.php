<?php
class MitiTabelaTest extends PHPUnit_Framework_TestCase{
	protected $MitiTabela;
	
	protected function setUp(){
		$this->MitiTabela=new MitiTabela('categorias');
	}
	
	public function testGetNome(){
		$this->assertSame('categorias',$this->MitiTabela->getNome());
	}
	
	public function testGetTipos(){
		$teste=array('id'=>'float','nome'=>'string','status'=>'float');
		$this->assertSame($teste,$this->MitiTabela->getTipos());
	}
	
	public function testGetAnulaveis(){
		$teste=array('id'=>false,'nome'=>false,'status'=>true);
		$this->assertSame($teste,$this->MitiTabela->getAnulaveis());
	}
	
	public function testGetTamanhos(){
		$teste=array('id'=>3,'nome'=>30,'status'=>3);
		$this->assertSame($teste,$this->MitiTabela->getTamanhos());
	}
	
	public function testGetPkCampo(){
		$this->assertSame('id',$this->MitiTabela->getPkCampo());
	}
	
	public function testGetPkTipo(){
		$this->assertSame('float',$this->MitiTabela->getPkTipo());
	}
}