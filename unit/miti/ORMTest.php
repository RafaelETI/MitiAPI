<?php
class ORMTest extends PHPUnit_Framework_TestCase{
	private static $ORM;
	private static $ORMMemoria;
	private static $ORMStatus;
	
	public static function setUpBeforeClass(){
		self::$ORM = new \miti\ORM('categoria', 'c');
		self::$ORMMemoria = new \miti\ORM('memoria', 'm');
		self::$ORMStatus = new \miti\ORM('status', 's');
	}
	
	public function testGetBanco(){
		$this->assertTrue(is_a(self::$ORM->getBanco(), '\miti\Banco'));
	}
	
	public function testGetTipos(){
		$tipos = array('id' => 'float', 'nome' => 'string', 'status' => 'string');
		$this->assertSame($tipos, self::$ORM->getTipos());
	}
	
	public function testGetAnulaveis(){
		$anulaveis = array('id' => false, 'nome' => false, 'status' => true);
		$this->assertSame($anulaveis, self::$ORM->getAnulaveis());
	}
	
	public function testGetTamanhos(){
		$tamanhos = array('id' => 3, 'nome' => 30, 'status' => 1);
		$this->assertSame($tamanhos, self::$ORM->getTamanhos());
	}
	
	public function testGetPk(){
		$this->assertSame('id', self::$ORM->getPk());
	}
	
	public function testValidarVazio(){
		$this->setExpectedException('UnexpectedValueException', "Valor vazio para o campo 'id'.");
		self::$ORM->criar(array('id' => ''));
	}
	
	public function testValidarExcessoDeCaracteres(){
		$mensagem = "Limite de caractéres excedido para o campo 'id'.";
		$this->setExpectedException('UnexpectedValueException', $mensagem);
		
		self::$ORM->criar(array('id' => 1000));
	}
	
	public function testCriar(){
		self::$ORM->criar(array('id' => 4, 'nome' => 'Teste', 'status' => 'c'));
		self::$ORM->criar(array('id' => 5, 'nome' => 'Teste 2', 'status' => ''));
	}
	
	public function testAtualizar(){
		self::$ORM->atualizar(array('status' => 'c'), 5);
	}
	
	public function testDeletarArray(){
		self::$ORM->deletar(array('status' => 'c'));
		
		$quantidade = self::$ORM
			->selecionar('c', 'id')
			->filtrar('c', 'status', '=', 'c')
			->ler()
			->quantificar()
		;
		
		$this->assertSame(0, $quantidade);
	}
	
	public function testTratarPk(){
		self::$ORMStatus->criar(array('id' => 'd', 'descricao' => 'Teste', 'prioridade' => 1));
		self::$ORMStatus->deletar('d');
	}
	
	public function testJuntar(){
		$m = self::$ORMMemoria
			->selecionar('m', 'id')
			->selecionar('s', 'descricao', 'des')
			->juntar('categoria', 'c', 'm', 'categoria', 'c', 'id')
			->juntar('status', 's', 'c', 'status', 's', 'id')
			->filtrar('s', 'id', '=', 'a')
			->ler()
			->vetorizar()
		;
		
		$this->assertSame(array('id' => '1', 'des' => 'Ativo'), $m);
	}
	
	public function testJuntarEsquerda(){
		self::$ORM->zerar();
		
		$c = self::$ORM
			->selecionar('c', 'nome')
			->juntarEsquerda('status', 's', 'c', 'status', 's', 'id')
			->filtrar('c', 'id', '=', '3')
			->ler()
			->vetorizar()
		;
		
		$this->assertSame(array('nome' => 'Pintura'), $c);
	}
	
	public function testJuntarDireita(){
		$s = self::$ORMStatus
			->selecionar('c', 'nome', 'c_nome')
			->juntarDireita('categoria', 'c', 's', 'id', 'c', 'status')
			->filtrar('c', 'id', '=', '3')
			->ler()
			->vetorizar()
		;
		
		$this->assertSame(array('c_nome' => 'Pintura'), $s);
	}
	
	public function testEFiltrar(){
		self::$ORMMemoria->zerar();
		
		$m = self::$ORMMemoria
			->selecionar('m', 'id')
			->filtrar('m', 'categoria', '=', '1')
			->eFiltrar('m', 'descricao', '=', 'Peaceful Warrior')
			->ler()
			->vetorizar()
		;
		
		$this->assertSame(array('id' => '1'), $m);
	}
	
	public function testOuFiltrar(){
		self::$ORMMemoria->zerar();
		
		$m = self::$ORMMemoria
			->selecionar('m', 'id')
			->filtrar('m', 'id', '=', '1')
			->ouFiltrar('m', 'id', '=', '2')
			->ler()
			->quantificar()
		;
		
		$this->assertSame(2, $m);
	}
	
	public function testTratarLeitura(){
		self::$ORM->zerar();
		$c = self::$ORM->selecionar('c', 'id')->filtrar('c', 'nome', 'like', 'ilm')->ler()->quantificar();
		$this->assertSame(1, $c);
	}
	
	public function testOrdenar(){
		self::$ORMMemoria->zerar();
		
		$m = self::$ORMMemoria
			->selecionar('m', 'descricao')
			->ordenar('m', 'categoria', 'asc')
			->ordenar('m', 'descricao', 'desc')
			->ler()
			->vetorizar()
		;
		
		$this->assertSame(array('descricao' => 'The Village'), $m);
	}
	
	public function testOrdenarAleatoriamente(){
		self::$ORMMemoria->zerar();
		self::$ORMMemoria->selecionar('m', 'id')->ordenarAleatoriamente();
		
		$resultado = false;
		$controle = self::$ORMMemoria->ler()->vetorizar();
		
		for($i = 1; $i <= 10; $i++){
			$m = self::$ORMMemoria->ler()->vetorizar();
			
			if($m['id'] != $controle['id']){
				$resultado = true;
				break;
			}
		}
		
		$this->assertTrue($resultado);
	}
	
	public function testAgrupar(){
		$m = self::$ORMMemoria
			->selecionar('s', 'id')
			->juntar('categoria', 'c', 'm', 'categoria', 'c', 'id')
			->juntar('status', 's', 'c', 'status', 's', 'id')
			->agrupar('s', 'prioridade')
			->ler()
			->quantificar()
		;
		
		$this->assertSame(1, $m);
	}
	
	public function testLimitarZero(){
		self::$ORMMemoria->zerar();
		$quantidade = self::$ORMMemoria->selecionar('m', 'id')->limitar(0)->ler()->quantificar();
		$this->assertSame(3, $quantidade);
	}
	
	public function testLimitar(){
		self::$ORMMemoria->zerar();
		$quantidade = self::$ORMMemoria->selecionar('m', 'id')->limitar(1, 2)->ler()->quantificar();
		$this->assertSame(1, $quantidade);
	}
	
	public function testZerar(){
		self::$ORMMemoria->zerar()->limitar(1)->zerar();
		$quantidade = self::$ORMMemoria->selecionar('m', 'id')->ler()->quantificar();
		$this->assertSame(3, $quantidade);
	}
	
	public static function tearDownAfterClass(){
		self::$ORM->getBanco()->rebobinar();
		self::$ORMMemoria->getBanco()->rebobinar();
		self::$ORMStatus->getBanco()->rebobinar();
	}
}
