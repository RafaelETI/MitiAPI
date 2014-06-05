<?php
class MitiORMTest extends PHPUnit_Framework_TestCase{
	public function testCriar(){
		$MitiORM=new MitiORM('categoria');
		$MitiBD=$MitiORM->criar(array('id'=>4,'nome'=>'Teste','status'=>'a'));
		
		$this->assertSame(
			array('nome'=>'Teste','status'=>'a'),
		
			$MitiORM
				->selecionar('c','nome')
				->eSelecionar('c','status')
				->filtrar('c','id','=',4)
				->ler()
				->obterAssoc()
		);
		
		$MitiBD->rebobinar();
	}
	
	public function testAtualizar(){
		$MitiORM=new MitiORM('categoria');
		$MitiORM->atualizar(array('status'=>'b'),3);
		
		$this->assertSame(
			array('status'=>'b'),
		
			$MitiORM
				->selecionar('c','status')
				->filtrar('c','id','=',3)
				->ler()
				->obterAssoc()
		);
		
		$MitiBD=$MitiORM->atualizar(array('status'=>''),3);
		$MitiBD->cometer();
	}
	
	public function testValidarVazio(){
		$this->setExpectedException('Exception','Valor vazio');
		
		$MitiORM=new MitiORM('categoria');
		$MitiBD=$MitiORM->criar(array('id'=>''));
		$MitiBD->rebobinar();
	}
	
	public function testValidarExcessoCaracteres(){
		$this->setExpectedException('Exception','Limite de caractéres excedido');
		
		$MitiORM=new MitiORM('categoria');
		$MitiBD=$MitiORM->criar(array('id'=>1000));
		$MitiBD->rebobinar();
	}
	
	public function testDeletarArray(){
		$MitiORM=new MitiORM('categoria');
		$MitiORM->criar(array('id'=>4,'nome'=>'Teste','status'=>'c'));
		$MitiORM->criar(array('id'=>5,'nome'=>'Teste 2','status'=>'c'));
		
		$MitiBD=$MitiORM->deletar(array('status'=>'c'));
		
		$this->assertSame(
			0,
			
			$MitiORM
				->selecionar('c','id')
				->filtrar('c','status','=','c')
				->ler()
				->obterQuantidade()
		);
		
		$MitiBD->cometer();
	}
	
	public function testTratarPk(){
		$MitiORM=new MitiORM('status');
		$MitiORM->criar(array('id'=>'d','descricao'=>'Teste','prioridade'=>1));
		$MitiBD=$MitiORM->deletar('d');
		
		$this->assertSame(
			0,
			
			$MitiORM
				->selecionar('s','id')
				->filtrar('s','id','=','d')
				->ler()
				->obterQuantidade()
		);
		
		$MitiBD->cometer();
	}
	
	public function testJuntar(){
		$MitiORM=new MitiORM('memoria');
		
		$this->assertSame(
			array('id'=>'1','des'=>'Ativo'),
		
			$MitiORM
				->selecionar('m','id')
				->eSelecionar('s','descricao','des')
				->juntar('join','categoria','c','m','categoria','c','id')
				->eJuntar('join','status','s','c','status','s','id')
				->filtrar('s','id','=','a')
				->ler()
				->obterAssoc()
		);
	}
	
	public function testEFiltrar(){
		$MitiORM=new MitiORM('memoria');
		
		$this->assertSame(
			array('id'=>'1'),
		
			$MitiORM
				->selecionar('m','id')
				->filtrar('m','categoria','=','1')
				->eFiltrar('m','descricao','=','Peaceful Warrior')
				->ler()
				->obterAssoc()
		);
	}
	
	public function testOuFiltrar(){
		$MitiORM=new MitiORM('memoria');
		
		$this->assertSame(
			2,
		
			$MitiORM
				->selecionar('m','id')
				->filtrar('m','id','=','1')
				->ouFiltrar('m','id','=','2')
				->ler()
				->obterQuantidade()
		);
	}
	
	public function testTratarLeitura(){
		$MitiORM=new MitiORM('categoria');
		
		$this->assertSame(
			1,
		
			$MitiORM
				->selecionar('c','id')
				->filtrar('c','nome','like','ilm')
				->ler()
				->obterQuantidade()
		);
	}
	
	public function testOrdenar(){
		$MitiORM=new MitiORM('memoria');
		
		$this->assertSame(
			array('descricao'=>'The Village'),
		
			$MitiORM
				->selecionar('m','descricao')
				->ordenar('m','categoria','asc')
				->eOrdenar('m','descricao','desc')
				->ler()
				->obterAssoc()
		);
	}
	
	public function testOrdenarAleatoriamente(){
		$MitiORM=new MitiORM('memoria');
		$MitiORM->selecionar('m','id')->ordenarAleatoriamente();
		
		$resultado=false;
		$controle=$MitiORM->ler()->obterAssoc();
		
		for($x=1;$x<=10;$x++){
			$memoria=$MitiORM->ler()->obterAssoc();
			
			if($memoria['id']!=$controle['id']){
				$resultado=true;
				break;
			}
		}
		
		$this->assertTrue($resultado);
	}
	
	public function testAgrupar(){
		$MitiORM=new MitiORM('memoria');
		
		$this->assertSame(
			1,
		
			$MitiORM
				->selecionar('s','id')
				->juntar('join','categoria','c','m','categoria','c','id')
				->eJuntar('join','status','s','c','status','s','id')
				->agrupar('s','prioridade')
				->ler()
				->obterQuantidade()
		);
	}
	
	public function testLimitar(){
		$MitiORM=new MitiORM('memoria');
		
		$this->assertSame(
			1,$MitiORM->selecionar('m','id')->limitar(1,2)->ler()->obterQuantidade()
		);
	}
}
