<?php
class MitiORM2Test extends PHPUnit_Framework_TestCase{
	public function testJuntar(){
		$MitiORM2=new MitiORM2('memoria');
		
		$this->assertSame(
			//array('id'=>'a','des'=>'Ativo'),
			3,
		
			$MitiORM2
				->selecionar('s','id')
				->eSelecionar('s','descricao','des')
				->juntar('join','categoria','c','m','categoria','c','id')
				->juntar('join','status','s','c','status','s','id')
				->filtrar('c','status','=','b')
				->ouFiltrar('c','nome','like','ilm')
				->ler()
				->obterQuantidade()
		);
	}
}
