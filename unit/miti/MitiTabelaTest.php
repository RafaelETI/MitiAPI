<?php
class MitiTabelaTest extends PHPUnit_Framework_TestCase{
	private $MitiTabela;
	
	protected function setUp(){
		$this->MitiTabela=new MitiTabela('categoria');
	}
	
	public function testGetNome(){
		$this->assertSame('categoria',$this->MitiTabela->getNome());
	}
	
	public function testGetTipos(){
		$tipos=array('id'=>'float','nome'=>'string','status'=>'string');
		$this->assertSame($tipos,$this->MitiTabela->getTipos());
	}
	
	public function testGetAnulaveis(){
		$anulaveis=array('id'=>false,'nome'=>false,'status'=>true);
		$this->assertSame($anulaveis,$this->MitiTabela->getAnulaveis());
	}
	
	public function testGetTamanhos(){
		$tamanhos=array('id'=>3,'nome'=>30,'status'=>1);
		$this->assertSame($tamanhos,$this->MitiTabela->getTamanhos());
	}
	
	public function testGetPkCampo(){
		$this->assertSame('id',$this->MitiTabela->getPkCampo());
	}
	
	public function testGetPkTipo(){
		$this->assertSame('float',$this->MitiTabela->getPkTipo());
	}
}
