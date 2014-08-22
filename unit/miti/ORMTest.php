<?php
class ORMTest extends PHPUnit_Framework_TestCase{
	public function testGetTipos(){
		$ORM=new \miti\ORM('categoria');
		$tipos=array('id'=>'float','nome'=>'string','status'=>'string');
		$this->assertSame($tipos,$ORM->getTipos());
	}
	
	public function testGetAnulaveis(){
		$ORM=new \miti\ORM('categoria');
		$anulaveis=array('id'=>false,'nome'=>false,'status'=>true);
		$this->assertSame($anulaveis,$ORM->getAnulaveis());
	}
	
	public function testGetTamanhos(){
		$ORM=new \miti\ORM('categoria');
		$tamanhos=array('id'=>3,'nome'=>30,'status'=>1);
		$this->assertSame($tamanhos,$ORM->getTamanhos());
	}
	
	public function testGetPk(){
		$ORM=new \miti\ORM('categoria');
		$this->assertSame('id',$ORM->getPk());
	}
	
	public function testValidarVazio(){
		$this->setExpectedException('Exception',"Valor vazio para o campo 'id'.");
		
		$ORM=new \miti\ORM('categoria');
		$ORM->criar(array('id'=>''));
	}
	
	public function testValidarExcessoDeCaracteres(){
		$mensagem="Limite de caractéres excedido para o campo 'id'.";
		$this->setExpectedException('Exception',$mensagem);
		
		$ORM=new \miti\ORM('categoria');
		$ORM->criar(array('id'=>1000));
	}
	
	public function testCriar(){
		$ORM=new \miti\ORM('categoria');
		$ORM->criar(array('id'=>4,'nome'=>'Teste','status'=>'c'));
		$ORM->criar(array('id'=>5,'nome'=>'Teste 2','status'=>''))->cometer();
	}
	
	public function testAtualizar(){
		$ORM=new \miti\ORM('categoria');
		$ORM->atualizar(array('status'=>'c'),5)->cometer();
	}
	
	public function testDeletarArray(){
		$ORM=new \miti\ORM('categoria');
		$Banco=$ORM->deletar(array('status'=>'c'));
		
		$categoria=$ORM
			->selecionar('c','id')
			->filtrar('c','status','=','c')
			->ler()
			->obterQuantidade()
		;
		
		$Banco->cometer();
		
		$this->assertSame(0,$categoria);
	}
	
	public function testTratarPk(){
		$ORM=new \miti\ORM('status');
		$ORM->criar(array('id'=>'d','descricao'=>'Teste','prioridade'=>1));
		$ORM->deletar('d')->cometer();
	}
	
	public function testJuntar(){
		$ORM=new \miti\ORM('memoria');
		
		$memoria=$ORM
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
		$ORM=new \miti\ORM('memoria');
		
		$memoria=$ORM
			->selecionar('m','id')
			->filtrar('m','categoria','=','1')
			->eFiltrar('m','descricao','=','Peaceful Warrior')
			->ler()
			->obterAssoc()
		;
		
		$this->assertSame(array('id'=>'1'),$memoria);
	}
	
	public function testOuFiltrar(){
		$ORM=new \miti\ORM('memoria');
		
		$memoria=$ORM
			->selecionar('m','id')
			->filtrar('m','id','=','1')
			->ouFiltrar('m','id','=','2')
			->ler()
			->obterQuantidade()
		;
		
		$this->assertSame(2,$memoria);
	}
	
	public function testTratarLeitura(){
		$ORM=new \miti\ORM('categoria');
		
		$categoria=$ORM
			->selecionar('c','id')
			->filtrar('c','nome','like','ilm')
			->ler()
			->obterQuantidade()
		;
		
		$this->assertSame(1,$categoria);
	}
	
	public function testOrdenar(){
		$ORM=new \miti\ORM('memoria');
		
		$memoria=$ORM
			->selecionar('m','descricao')
			->ordenar('m','categoria','asc')
			->ordenar('m','descricao','desc')
			->ler()
			->obterAssoc()
		;
		
		$this->assertSame(array('descricao'=>'The Village'),$memoria);
	}
	
	public function testOrdenarAleatoriamente(){
		$ORM=new \miti\ORM('memoria');
		$ORM->selecionar('m','id')->ordenarAleatoriamente();
		
		$resultado=false;
		$controle=$ORM->ler()->obterAssoc();
		
		for($x=1;$x<=10;$x++){
			$memoria=$ORM->ler()->obterAssoc();
			
			if($memoria['id']!=$controle['id']){
				$resultado=true;
				break;
			}
		}
		
		$this->assertTrue($resultado);
	}
	
	public function testAgrupar(){
		$ORM=new \miti\ORM('memoria');
		
		$memoria=$ORM
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
		$ORM=new \miti\ORM('memoria');
		$quantidade=$ORM->selecionar('m','id')->limitar(0)->ler()->obterQuantidade();
		$this->assertSame(3,$quantidade);
	}
	
	public function testLimitar(){
		$ORM=new \miti\ORM('memoria');
		$quantidade=$ORM->selecionar('m','id')->limitar(1,2)->ler()->obterQuantidade();
		$this->assertSame(1,$quantidade);
	}
}
