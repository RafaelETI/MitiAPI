<?php
class MitiORMTest extends PHPUnit_Framework_TestCase{
	public function testValidarVazio(){
		$this->setExpectedException('Exception',"Valor vazio para o campo 'id'.");
		
		$MitiORM=new MitiORM('categoria');
		$MitiORM->criar(array('id'=>''));
	}
	
	public function testValidarExcessoDeCaracteres(){
		$mensagem="Limite de caractéres excedido para o campo 'id'.";
		$this->setExpectedException('Exception',$mensagem);
		
		$MitiORM=new MitiORM('categoria');
		$MitiORM->criar(array('id'=>1000));
	}
	
	public function testCriar(){
		$MitiORM=new MitiORM('categoria');
		$MitiORM->criar(array('id'=>4,'nome'=>'Teste','status'=>'c'));
		$MitiORM->criar(array('id'=>5,'nome'=>'Teste 2','status'=>''))->cometer();
	}
	
	public function testAtualizar(){
		$MitiORM=new MitiORM('categoria');
		$MitiORM->atualizar(array('status'=>'c'),5)->cometer();
	}
	
	public function testDeletarArray(){
		$MitiORM=new MitiORM('categoria');
		$MitiBD=$MitiORM->deletar(array('status'=>'c'));
		
		$categoria=$MitiORM
			->selecionar('c','id')
			->filtrar('c','status','=','c')
			->ler()
			->obterQuantidade()
		;
		
		$MitiBD->cometer();
		
		$this->assertSame(0,$categoria);
	}
	
	public function testTratarPk(){
		$MitiORM=new MitiORM('status');
		$MitiORM->criar(array('id'=>'d','descricao'=>'Teste','prioridade'=>1));
		$MitiORM->deletar('d')->cometer();
	}
	
	public function testJuntar(){
		$MitiORM=new MitiORM('memoria');
		
		$memoria=$MitiORM
			->selecionar('m','id')
			->selecionar('s','descricao','des')
			->juntar('join','categoria','c','m','categoria','c','id')
			->juntar('join','status','s','c','status','s','id')
			->filtrar('s','id','=','a')
			->ler()
			->obterAssoc()
		;
		
		$this->assertSame(array('id'=>'1','des'=>'Ativo'),$memoria);
	}
	
	public function testEFiltrar(){
		$MitiORM=new MitiORM('memoria');
		
		$memoria=$MitiORM
			->selecionar('m','id')
			->filtrar('m','categoria','=','1')
			->eFiltrar('m','descricao','=','Peaceful Warrior')
			->ler()
			->obterAssoc()
		;
		
		$this->assertSame(array('id'=>'1'),$memoria);
	}
	
	public function testOuFiltrar(){
		$MitiORM=new MitiORM('memoria');
		
		$memoria=$MitiORM
			->selecionar('m','id')
			->filtrar('m','id','=','1')
			->ouFiltrar('m','id','=','2')
			->ler()
			->obterQuantidade()
		;
		
		$this->assertSame(2,$memoria);
	}
	
	public function testTratarLeitura(){
		$MitiORM=new MitiORM('categoria');
		
		$categoria=$MitiORM
			->selecionar('c','id')
			->filtrar('c','nome','like','ilm')
			->ler()
			->obterQuantidade()
		;
		
		$this->assertSame(1,$categoria);
	}
	
	public function testOrdenar(){
		$MitiORM=new MitiORM('memoria');
		
		$memoria=$MitiORM
			->selecionar('m','descricao')
			->ordenar('m','categoria','asc')
			->ordenar('m','descricao','desc')
			->ler()
			->obterAssoc()
		;
		
		$this->assertSame(array('descricao'=>'The Village'),$memoria);
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
		
		$memoria=$MitiORM
			->selecionar('s','id')
			->juntar('join','categoria','c','m','categoria','c','id')
			->juntar('join','status','s','c','status','s','id')
			->agrupar('s','prioridade')
			->ler()
			->obterQuantidade()
		;
		
		$this->assertSame(1,$memoria);
	}
	
	public function testLimitarZero(){
		$MitiORM=new MitiORM('memoria');
		$quantidade=$MitiORM->selecionar('m','id')->limitar(0)->ler()->obterQuantidade();
		$this->assertSame(3,$quantidade);
	}
	
	public function testLimitar(){
		$MitiORM=new MitiORM('memoria');
		$quantidade=$MitiORM->selecionar('m','id')->limitar(1,2)->ler()->obterQuantidade();
		$this->assertSame(1,$quantidade);
	}
}
