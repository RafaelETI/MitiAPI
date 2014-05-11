<?php
class MitiORMTest extends PHPUnit_Framework_TestCase{
	protected $MitiORM;
	
	protected function setUp(){
		$this->MitiORM=new MitiORM('categoria');
	}
	
	public function testCriar(){
		$this->MitiORM->criar(array('id'=>2,'nome'=>'Teste','status'=>1));
		
		$this->assertSame(
			array('id'=>'2','nome'=>'Teste','status'=>'1'),
		
			$this->MitiORM
				->definirCampos(array('id','nome','status'))
				->ler(array('id'=>array('=',2)))
				->obterAssoc()
		);
		
		$this->MitiORM->deletar(2);
	}
	
	public function testAtualizar(){
		$this->MitiORM->atualizar(array('status'=>1),1);
		
		$this->assertSame(
			array('status'=>'1'),
		
			$this->MitiORM
				->definirCampos(array('status'))
				->ler(array('id'=>array('=',1)))
				->obterAssoc()
		);
		
		$this->MitiORM->atualizar(array('status'=>''),1);
	}
	
	public function testValidarVazio(){
		$this->setExpectedException('Exception','Valor vazio');
		$this->MitiORM->criar(array('id'=>''));
	}
	
	public function testValidarExcessoCaracteres(){
		$this->setExpectedException('Exception','Limite de caractéres excedido');
		$this->MitiORM->criar(array('id'=>1000));
	}
	
	public function testJuntar(){
		$this->assertSame(
			array('id'=>'1','m_descricao'=>'Spartacus (2004)'),
		
			$this->MitiORM
				->setJoins(array('join'))
				->setAliases(array('m'))
				->setOnTabelas(array('categoria'))
				->setTabelaChaves(array('id'))
				->setTabelasChaves(array('categoria'))
				->juntar(array('memoria'))
				->definirCampos(array('id'),array(array('descricao')))
				->ler(
					array(),
					array(array('id'=>array('=',2)))
				)->obterAssoc()
		);
	}
	
	public function testTratarLeitura(){
		$this->assertSame(
			1,
		
			$this->MitiORM
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
		$this->assertSame(
			array('id'=>'1','m_id'=>'3'),
		
			$this->MitiORM
				->setJoins(array('join'))
				->setAliases(array('m'))
				->setOnTabelas(array('categoria'))
				->setTabelaChaves(array('id'))
				->setTabelasChaves(array('categoria'))
				->juntar(array('memoria'))
				->definirCampos(array('id'),array(array('id')))
				->ordenar(array(),array(array('id'=>'desc')))
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
			1,$MitiORM->definirCampos(array('id'))->ler()->obterQuantidade()
		);
	}
	
	public function testAgruparTabelaExterna(){
		$this->assertSame(
			1,
		
			$this->MitiORM
				->setJoins(array('join'))
				->setAliases(array('m'))
				->setOnTabelas(array('categoria'))
				->setTabelaChaves(array('id'))
				->setTabelasChaves(array('categoria'))
				->juntar(array('memoria'))
				->definirCampos(array('id'))
				->agrupar(array(),array(array('categoria')))
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
		$this->MitiORM->criar(array('id'=>2,'nome'=>'Teste','status'=>1));
		$this->MitiORM->criar(array('id'=>3,'nome'=>'Teste 2','status'=>1));
		
		$this->MitiORM->deletar(array('status'=>1));
		
		$this->assertSame(
			0,
			
			$this->MitiORM
				->definirCampos(array('id'))
				->ler(array('status'=>array('=',1)))
				->obterQuantidade()
		);
	}
}
