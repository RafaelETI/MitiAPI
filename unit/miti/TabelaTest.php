<?php
class TabelaTest extends PHPUnit_Framework_TestCase{
	private $Tabela;
	
	protected function setUp(){
		$this->Tabela=new \miti\Tabela('categoria');
	}
	
	public function testGetNome(){
		$this->assertSame('categoria',$this->Tabela->getNome());
	}
	
	public function testGetTipos(){
		$tipos=array('id'=>'float','nome'=>'string','status'=>'string');
		$this->assertSame($tipos,$this->Tabela->getTipos());
	}
	
	public function testGetAnulaveis(){
		$anulaveis=array('id'=>false,'nome'=>false,'status'=>true);
		$this->assertSame($anulaveis,$this->Tabela->getAnulaveis());
	}
	
	public function testGetTamanhos(){
		$tamanhos=array('id'=>3,'nome'=>30,'status'=>1);
		$this->assertSame($tamanhos,$this->Tabela->getTamanhos());
	}
	
	public function testGetPkCampo(){
		$this->assertSame('id',$this->Tabela->getPkCampo());
	}
	
	public function testGetPkTipo(){
		$this->assertSame('float',$this->Tabela->getPkTipo());
	}
}
