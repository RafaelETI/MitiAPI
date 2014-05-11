<?php
class MitiORMTest extends PHPUnit_Framework_TestCase{
	public function testCriar(){
		$MitiORM=new MitiORM('categoria');
		$MitiORM->criar(array('id'=>4,'nome'=>'Teste','status'=>'a'));
		
		$this->assertSame(
			array('nome'=>'Teste','status'=>'a'),
		
			$MitiORM
				->definirCampos(array('nome','status'))
				->ler(array('id'=>array('=',4)))
				->obterAssoc()
		);
		
		$MitiORM->deletar(4);
	}
	
	public function testAtualizar(){
		$MitiORM=new MitiORM('categoria');
		$MitiORM->atualizar(array('status'=>'b'),3);
		
		$this->assertSame(
			array('status'=>'b'),
		
			$MitiORM
				->definirCampos(array('status'))
				->ler(array('id'=>array('=',3)))
				->obterAssoc()
		);
		
		$MitiORM->atualizar(array('status'=>''),3);
	}
	
	public function testValidarVazio(){
		$this->setExpectedException('Exception','Valor vazio');
		
		$MitiORM=new MitiORM('categoria');
		$MitiORM->criar(array('id'=>''));
	}
	
	public function testValidarExcessoCaracteres(){
		$this->setExpectedException('Exception','Limite de caractéres excedido');
		
		$MitiORM=new MitiORM('categoria');
		$MitiORM->criar(array('id'=>1000));
	}
	
	public function testJuntar(){
		$MitiORM=new MitiORM('memoria');
		
		$this->assertSame(
			array('id'=>'1','s_descricao'=>'Ativo'),
		
			$MitiORM
				->setJoins(array('join','join'))
				->setAliases(array('c','s'))
				->setOnTabelas(array('memoria','c'))
				->setTabelaChaves(array('categoria','status'))
				->setTabelasChaves(array('id','id'))
				->juntar(array('categoria','status'))
				->definirCampos(
					array('id'),array(1=>array('descricao'))
				)->ler(
					array(),
					array(1=>array('id'=>array('=','a')))
				)->obterAssoc()
		);
	}
	
	public function testTratarLeitura(){
		$MitiORM=new MitiORM('categoria');
		
		$this->assertSame(
			1,
		
			$MitiORM
				->definirCampos(array('id'))
				->ler(array('nome'=>array('like','ilm')))
				->obterQuantidade()
		);
	}
	
	public function testOrdenar(){
		$MitiORM=new MitiORM('memoria');
		$MitiORM->ordenar(array('id'=>'desc'));
		
		$this->assertSame(
			array('id'=>'3'),
			$MitiORM->definirCampos(array('id'))->ler()->obterAssoc()
		);
	}
	
	public function testOrdenarTabelaExterna(){
		$MitiORM=new MitiORM('memoria');
		
		$this->assertSame(
			array('id'=>'3','s_id'=>'b'),
		
			$MitiORM
				->setJoins(array('join','join'))
				->setAliases(array('c','s'))
				->setOnTabelas(array('memoria','c'))
				->setTabelaChaves(array('categoria','status'))
				->setTabelasChaves(array('id','id'))
				->juntar(array('categoria','status'))
				->definirCampos(array('id'),array(1=>array('id')))
				->ordenar(array(),array(1=>array('id'=>'desc')))
				->ler()
				->obterAssoc()
		);
	}
	
	public function testOrdenarAleatoriamente(){
		$MitiORM=new MitiORM('memoria');
		$MitiORM->ordenarAleatoriamente();
		
		$resultado=false;
		$controle=$MitiORM->definirCampos(array('id'))->ler()->obterAssoc();
		
		for($x=1;$x<=10;$x++){
			$memoria=$MitiORM->definirCampos(array('id'))->ler()->obterAssoc();
			
			if($memoria['id']!=$controle['id']){
				$resultado=true;
				break;
			}
		}
		
		$this->assertTrue($resultado);
	}
	
	public function testAgrupar(){
		$MitiORM=new MitiORM('memoria');
		$MitiORM->agrupar(array('categoria'));
		
		$this->assertSame(
			2,$MitiORM->definirCampos(array('id'))->ler()->obterQuantidade()
		);
	}
	
	public function testAgruparTabelaExterna(){
		$MitiORM=new MitiORM('memoria');
		
		$this->assertSame(
			1,
		
			$MitiORM
				->setJoins(array('join','join'))
				->setAliases(array('c','s'))
				->setOnTabelas(array('memoria','c'))
				->setTabelaChaves(array('categoria','status'))
				->setTabelasChaves(array('id','id'))
				->juntar(array('categoria','status'))
				->definirCampos(array('id'))
				->agrupar(array(),array(1=>array('prioridade')))
				->ler()
				->obterQuantidade()
		);
	}
	
	public function testLimitar(){
		$MitiORM=new MitiORM('memoria');
		$MitiORM->limitar(1,2);
		
		$this->assertSame(
			1,$MitiORM->definirCampos(array('id'))->ler()->obterQuantidade()
		);
	}
	
	public function testDeletarArray(){
		$MitiORM=new MitiORM('categoria');
		$MitiORM->criar(array('id'=>4,'nome'=>'Teste','status'=>'c'));
		$MitiORM->criar(array('id'=>5,'nome'=>'Teste 2','status'=>'c'));
		
		$MitiORM->deletar(array('status'=>'c'));
		
		$this->assertSame(
			0,
			
			$MitiORM
				->definirCampos(array('id'))
				->ler(array('status'=>array('=','c')))
				->obterQuantidade()
		);
	}
	
	public function testTratarPk(){
		$MitiORM=new MitiORM('status');
		$MitiORM->criar(array('id'=>'d','descricao'=>'Teste'));
		$MitiORM->deletar('d');
		
		$this->assertSame(
			0,
			
			$MitiORM
				->definirCampos(array('id'))
				->ler(array('id'=>array('=','d')))
				->obterQuantidade()
		);
	}
}
