<?php
class MitiORMTest extends PHPUnit_Framework_TestCase{
	protected $MitiORM;
	
	protected function setUp(){
		$this->MitiORM=new MitiORM('categorias');
	}
	
	public function testCriar(){
		$this->MitiORM->criar(array('id'=>2,'nome'=>'\'Tes\te"','status'=>'aaa'));
		
		$this->MitiORM->definirCampos(array('nome','status'));
		$teste=$this->MitiORM->ler(array('id'=>array('=',2)))->obterAssoc();
		$this->assertSame(array('nome'=>'\'Tes\te"','status'=>'0'),$teste);
	}
	
	public function testCriarValorVazio(){
		$this->setExpectedException('Exception','Valor vazio');
		$this->MitiORM->criar(array('id'=>6,'nome'=>''));
	}
	
	public function testCriarExcessoTamanho(){
		$this->setExpectedException('Exception','Limite de caractéres excedido');
		$this->MitiORM->criar(array('id'=>6,'nome'=>'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaab'));
	}
	
	public function testJuntar(){
		$this->MitiORM->setJoins(array('join'));
		$this->MitiORM->setAliases(array('m'));
		$this->MitiORM->setOnTabelas(array('categorias'));
		$this->MitiORM->setTabelaChaves(array('id'));
		$this->MitiORM->setTabelasChaves(array('categoria'));
		$this->MitiORM->juntar(array('memoria'));
		
		$this->MitiORM->definirCampos(array('id'),array(array('descricao')));
		
		$filtros=array('id'=>array('=','1'));
		$tabelas_filtros=array(array('descricao'=>array('like','hur')));
		$teste=$this->MitiORM->ler($filtros,$tabelas_filtros)->obterAssoc();
		
		$this->assertSame('Ben Hur (1959)',$teste['m_descricao']);
	}
	
	public function testOrdenar(){
		$this->MitiORM->definirCampos(array('nome'));
		$this->MitiORM->ordenar(array('id'=>'desc'));
		$teste=$this->MitiORM->ler()->obterAssoc();
		$this->assertSame('\'Tes\te"',$teste['nome']);
	}
	
	public function testLimitar(){
		$MitiORM=new MitiORM('memoria');
		$MitiORM->definirCampos(array('id'));
		$MitiORM->limitar(2,1);
		$this->assertSame(2,$MitiORM->ler()->obterQuantidade());
	}
	
	public function testTratarLeituraEscapar(){
		$this->MitiORM->definirCampos(array('id'));
		$filtros=array('nome'=>array('=','\'Tes\te"'));
		$qnt=$this->MitiORM->ler($filtros)->obterQuantidade();
		
		$this->assertSame(1,$qnt);
	}
	
	public function testTratarLeituraWildcard(){
		$this->MitiORM->definirCampos(array('id'));
		$filtros=array('nome'=>array('like','es'));
		$qnt=$this->MitiORM->ler($filtros)->obterQuantidade();
		
		$this->assertSame(1,$qnt);
	}
	
	public function testTratarLeituraSetType(){
		$this->MitiORM->definirCampos(array('id'));
		$filtros=array('status'=>array('=','tes'));
		$qnt=$this->MitiORM->ler($filtros)->obterQuantidade();
		
		$this->assertSame(1,$qnt);
	}
	
	public function testAtualizar(){
		$this->MitiORM->atualizar(array('nome'=>'Teste2','status'=>''),2);
		
		$this->MitiORM->definirCampos(array('nome','status'));
		$teste=$this->MitiORM->ler(array('id'=>array('=',2)))->obterAssoc();
		$this->assertSame(array('nome'=>'Teste2','status'=>null),$teste);
	}
	
	public function testDeletar(){
		$this->MitiORM->deletar(2);
	}
	
	public function testDeletarArray(){
		$this->MitiORM->criar(array('id'=>3,'nome'=>'Aaa','status'=>0));
		$this->MitiORM->criar(array('id'=>4,'nome'=>'Bbb','status'=>0));
		
		$this->MitiORM->deletar(array('status'=>0));
		
		$this->MitiORM->definirCampos(array('id'));
		$qnt=$this->MitiORM->ler(array('status'=>array('=',0)))->obterQuantidade();
		
		$this->assertSame(0,$qnt);
	}
	
	public function testDeletarScalar(){
		$MitiORM=$this->criarRegistroMemoria();
		$MitiORM->deletar('d');
		
		$MitiORM->definirCampos(array('id'));
		$qnt=$MitiORM->ler(array('id'=>array('=','d')))->obterQuantidade();
		
		$this->assertSame(0,$qnt);
	}
	
	private function criarRegistroMemoria(){
		$MitiORM=new MitiORM('memoria');
		$MitiORM->criar(array('id'=>'d','descricao'=>'Teste','categoria'=>1));
		return $MitiORM;
	}
}