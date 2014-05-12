<?php
class MitiORM2Test extends PHPUnit_Framework_TestCase{
	public function testJuntar(){
		$MitiORM2=new MitiORM2('memoria');
		
		$this->assertSame(
			array('id'=>'1','descricao'=>'Ativo'),
		
			$MitiORM2
				->selecionar('m','id')
				->eSelecionar('s','descricao')
				->juntar('join','categoria','c','m','categoria','c','id')
				->juntar('join','status','s','c','status','s','id')
				->filtrar('s','id','=','a')
				->ler()
				->obterAssoc()
		);
	}
	
	public function testTratarLeitura(){
		$MitiORM2=new MitiORM2('categoria');
		
		$this->assertSame(
			1,
		
			$MitiORM2
				->selecionar('c','id')
				->filtrar('c','nome','like','ilm')
				->ler()
				->obterQuantidade()
		);
	}
	
	public function testOrdenar(){
		$MitiORM2=new MitiORM2('memoria');
		
		$this->assertSame(
			array('id'=>'b'),
		
			$MitiORM2
				->selecionar('s','id')
				->juntar('join','categoria','c','m','categoria','c','id')
				->juntar('join','status','s','c','status','s','id')
				->ordenar('s','id','desc')
				->ler()
				->obterAssoc()
		);
	}
}
