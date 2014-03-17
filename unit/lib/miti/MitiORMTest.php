<?php
require_once 'Config.php'; Config::setInstance();

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
	
	public function testValidarValorVazioException(){
		$this->setExpectedException('Exception','Valor vazio');
		$this->MitiORM->criar(array('id'=>6,'nome'=>''));
	}
	
	public function testValidarTamanhoException(){
		$this->setExpectedException('Exception','Limite de caractéres excedido');
		$this->MitiORM->criar(array('id'=>6,'nome'=>'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaab'));
	}
	
	public function testSetJoins(){
		$this->MitiORM->setJoins(array('join'));
	}
	
	public function testSetAliases(){
		$this->MitiORM->setAliases(array('m'));
	}
	
	public function testSetOnTabelas(){
		$this->MitiORM->setOnTabelas(array('categorias'));
	}
	
	public function testSetTabelaChaves(){
		$this->MitiORM->setTabelaChaves(array('id'));
	}
	
	public function testSetTabelasChaves(){
		$this->MitiORM->setTabelasChaves(array('categoria'));
	}
	
	public function testJuntar(){
		$this->testSetJoins();
		$this->testSetAliases();
		$this->testSetOnTabelas();
		$this->testSetTabelaChaves();
		$this->testSetTabelasChaves();
		$this->MitiORM->juntar(array('memoria'));
		
		$this->MitiORM->definirCampos(array('id'),array(array('descricao')));
		
		$tabelas_filtros=array(array('descricao'=>array('like','hur')));
		$teste=$this->MitiORM->ler(array('id'=>array('=','1')),$tabelas_filtros)->obterAssoc();
		
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
	
	public function testLer(){
		$this->MitiORM->definirCampos(array('id'));
		$this->tratarLeituraEscapar();
		$this->tratarLeituraWildcard();
		$this->tratarLeituraSetType();
	}
	
	private function tratarLeituraEscapar(){
		$qnt=$this->MitiORM
			->ler(array('nome'=>array('=','\'Tes\te"')))
			->obterQuantidade();
		
		$this->assertSame(1,$qnt);
	}
	
	private function tratarLeituraWildcard(){
		$qnt=$this->MitiORM
			->ler(array('nome'=>array('like','es')))
			->obterQuantidade();
		
		$this->assertSame(1,$qnt);
	}
	
	private function tratarLeituraSetType(){
		$qnt=$this->MitiORM
			->ler(array('status'=>array('=','tes')))
			->obterQuantidade();
		
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
		
		$this->deletarArray();
		$this->deletarScalar();
	}
	
	private function deletarArray(){
		$this->MitiORM->criar(array('id'=>3,'nome'=>'Aaa','status'=>0));
		$this->MitiORM->criar(array('id'=>4,'nome'=>'Bbb','status'=>0));
		
		$this->MitiORM->deletar(array('status'=>0));
		
		$this->MitiORM->definirCampos(array('id'));
		$qnt=$this->MitiORM
			->ler(array('status'=>array('=',0)))
			->obterQuantidade();
		
		$this->assertSame(0,$qnt);
	}
	
	private function deletarScalar(){
		$MitiORM=$this->criarRegistroMemoria();
		$MitiORM->deletar('d');
		
		$MitiORM->definirCampos(array('id'));
		$qnt=$MitiORM
			->ler(array('id'=>array('=','d')))
			->obterQuantidade();
		
		$this->assertSame(0,$qnt);
	}
	
	private function criarRegistroMemoria(){
		$MitiORM=new MitiORM('memoria');
		$MitiORM->criar(array('id'=>'d','descricao'=>'Teste','categoria'=>1));
		return $MitiORM;
	}
}