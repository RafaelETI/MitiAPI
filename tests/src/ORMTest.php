<?php
use PHPUnit\Framework\TestCase;

class ORMTest extends TestCase{
	private static $config;
    private static $ormCategoria;
	private static $ormMemoria;
	private static $ormStatus;
	
	public static function setUpBeforeClass(){
		global $config;
        self::$config = $config;
		
		self::$ormCategoria = new \Miti\ORM($config, 'categoria', 'c');
		self::$ormMemoria = new \Miti\ORM($config, 'memoria', 'm');
		self::$ormStatus = new \Miti\ORM($config, 'status', 's');
	}
	
    public function testBanco(){
        $banco = (new \Miti\ORM(self::$config, 'categoria', 'c'))->setBanco(new \Miti\Banco(self::$config))->getBanco();
        $this->assertSame(true, $banco instanceof \Miti\Banco);
    }
    
	public function testGetTipos(){
		$tipos = ['id' => 'float', 'nome' => 'string', 'status' => 'string'];
		$this->assertSame($tipos, self::$ormCategoria->getTipos());
	}
	
	public function testGetAnulaveis(){
		$anulaveis = ['id' => false, 'nome' => false, 'status' => true];
		$this->assertSame($anulaveis, self::$ormCategoria->getAnulaveis());
	}
	
	public function testGetTamanhos(){
		$tamanhos = ['id' => 3, 'nome' => 30, 'status' => 1];
		$this->assertSame($tamanhos, self::$ormCategoria->getTamanhos());
	}
	
	public function testGetPk(){
		$this->assertSame('id', self::$ormCategoria->getPk());
	}
	
	public function testValidarVazio(){
		$this->setExpectedException('UnexpectedValueException', "Valor vazio para o campo 'id'.");
		self::$ormCategoria->criar(['id' => '']);
	}
	
	public function testValidarExcessoDeCaracteres(){
		$mensagem = "Limite de caractéres excedido para o campo 'id'.";
		$this->setExpectedException('UnexpectedValueException', $mensagem);
		
		self::$ormCategoria->criar(['id' => 1000]);
	}
	
	public function testCriar(){
		self::$ormCategoria->criar(['id' => 4, 'nome' => 'Teste', 'status' => 'c']);
		self::$ormCategoria->criar(['id' => 5, 'nome' => 'Teste 2', 'status' => '']);
	}
	
	public function testAtualizar(){
		self::$ormCategoria->filtrar('c', 'id', '=', '5')->atualizar(['status' => 'c']);
	}
	
	public function testDeletar(){
		self::$ormCategoria->zerar();
		
		self::$ormCategoria->filtrar('c', 'status', '=', 'c')->deletar();
		
		$quantidade = self::$ormCategoria->selecionar('c', 'id')->ler()->quantificar();
		$this->assertSame(0, $quantidade);
	}
	
	public function testJuntar(){
		$m = self::$ormMemoria
			->selecionar('m', 'id')
			->selecionar('s', 'descricao', 'des')
			->juntar('categoria', 'c', 'm', 'categoria', 'c', 'id')
			->juntar('status', 's', 'c', 'status', 's', 'id')
			->filtrar('s', 'id', '=', 'a')
			->ler()
			->vetorizar()
		;
		
		$this->assertSame(['id' => '1', 'des' => 'Ativo'], $m);
	}
	
	public function testJuntarEsquerda(){
		self::$ormCategoria->zerar();
		
		$c = self::$ormCategoria
			->selecionar('c', 'nome')
			->juntarEsquerda('status', 's', 'c', 'status', 's', 'id')
			->filtrar('c', 'id', '=', '3')
			->ler()
			->vetorizar()
		;
		
		$this->assertSame(['nome' => 'Pintura'], $c);
	}
	
	public function testJuntarDireita(){
		self::$ormStatus->zerar();
		
		$s = self::$ormStatus
			->selecionar('c', 'nome', 'c_nome')
			->juntarDireita('categoria', 'c', 's', 'id', 'c', 'status')
			->filtrar('c', 'id', '=', '3')
			->ler()
			->vetorizar()
		;
		
		$this->assertSame(['c_nome' => 'Pintura'], $s);
	}
	
	public function testEFiltrar(){
		self::$ormMemoria->zerar();
		
		$m = self::$ormMemoria
			->selecionar('m', 'id')
			->filtrar('m', 'categoria', '=', '1')
			->eFiltrar('m', 'descricao', '=', 'Peaceful Warrior')
			->ler()
			->vetorizar()
		;
		
		$this->assertSame(['id' => '1'], $m);
	}
	
	public function testOuFiltrar(){
		self::$ormMemoria->zerar();
		
		$m = self::$ormMemoria
			->selecionar('m', 'id')
			->filtrar('m', 'id', '=', '1')
			->ouFiltrar('m', 'id', '=', '2')
			->ler()
			->quantificar()
		;
		
		$this->assertSame(2, $m);
	}
	
	public function testTratarLeitura(){
		self::$ormCategoria->zerar();
		$c = self::$ormCategoria->selecionar('c', 'id')->filtrar('c', 'nome', 'like', 'ilm')->ler()->quantificar();
		$this->assertSame(1, $c);
	}
	
	public function testOrdenar(){
		self::$ormMemoria->zerar();
		
		$m = self::$ormMemoria
			->selecionar('m', 'descricao')
			->ordenar('m', 'categoria', 'asc')
			->ordenar('m', 'descricao', 'desc')
			->ler()
			->vetorizar()
		;
		
		$this->assertSame(['descricao' => 'The Village'], $m);
	}
	
	public function testOrdenarAleatoriamente(){
		self::$ormMemoria->zerar();
		self::$ormMemoria->selecionar('m', 'id')->ordenarAleatoriamente();
		
		$resultado = false;
		$controle = self::$ormMemoria->ler()->vetorizar();
		
		for($i = 1; $i <= 10; $i++){
			$m = self::$ormMemoria->ler()->vetorizar();
			
			if($m['id'] != $controle['id']){
				$resultado = true;
				break;
			}
		}
		
		$this->assertTrue($resultado);
	}
	
	public function testAgrupar(){
		self::$ormMemoria->zerar();
		
		$m = self::$ormMemoria
			->selecionar('s', 'prioridade')
			->juntar('categoria', 'c', 'c', 'id', 'm', 'categoria')
			->juntar('status', 's', 's', 'id', 'c', 'status')
			->agrupar('s', 'prioridade')
			->ler()
			->quantificar();
		
		$this->assertSame(1, $m);
	}
	
	public function testLimitarZero(){
		self::$ormMemoria->zerar();
		$quantidade = self::$ormMemoria->selecionar('m', 'id')->limitar(0)->ler()->quantificar();
		$this->assertSame(3, $quantidade);
	}
	
	public function testLimitar(){
		self::$ormMemoria->zerar();
		$quantidade = self::$ormMemoria->selecionar('m', 'id')->limitar(1, 2)->ler()->quantificar();
		$this->assertSame(1, $quantidade);
	}
	
	public function testZerar(){
		self::$ormMemoria->zerar()->limitar(1)->zerar();
		$quantidade = self::$ormMemoria->selecionar('m', 'id')->ler()->quantificar();
		$this->assertSame(3, $quantidade);
	}
	
	public static function tearDownAfterClass(){
		self::$ormCategoria->getBanco()->rebobinar();
		self::$ormMemoria->getBanco()->rebobinar();
		self::$ormStatus->getBanco()->rebobinar();
	}
}
