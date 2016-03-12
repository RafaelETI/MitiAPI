<?php
class BancoTest extends PHPUnit_Framework_TestCase{
	private static $config;
	private static $Banco;
	
	public static function setUpBeforeClass(){
		self::$config = ['banco' => ['servidor' => 'localhost', 'usuario' => 'root', 'senha' => 'root', 'nome' => 'miti_api', 'charset' => 'utf8']];
		self::$Banco = new \miti\Banco(self::$config);
	}
	
	public function testErroDeConexaoComMensagemTecnica(){
		$this->setExpectedException('RuntimeException', "Unknown database 'nao_existe'");
		
		ini_set('display_errors', 1);
		$config = self::$config;
		$config['banco']['nome'] = 'nao_existe';
		new \miti\Banco($config);
	}
	
	public function testErroDeConexaoComMensagemGenerica(){
		$mensagem = 'Não foi possível conectar ao banco de dados.';
		$this->setExpectedException('RuntimeException', $mensagem);
		
		ini_set('display_errors', 0);
		$config = self::$config;
		$config['banco']['nome'] = 'nao_existe';
		new \miti\Banco($config);
	}
	
	public function testErroDeCharset(){
		$mensagem = 'Houve um erro ao definir o charset.';
		$this->setExpectedException('DomainException', $mensagem);
		
		$config = self::$config;
		$config['banco']['charset'] = 'nao_existe';
		new \miti\Banco($config);
	}
	
	public function testEscaparArray(){
		$especiais = self::$Banco->escapar(array("'", '"', '\\'));
		$this->assertSame(array("\\'", '\\"', '\\\\'), $especiais);
	}
	
	public function testEscaparString(){
		$this->assertSame('\\\'\\"\\\\', self::$Banco->escapar('\'"\\'));
	}
	
	public function testErroDeRequisicaoComMensagemTecnica(){
		$mensagem =
			"#1062 Duplicate entry '1' for key 'PRIMARY' - "
			.'insert into categoria values(1, "Música", null)'
		;
		
		$this->setExpectedException('UnexpectedValueException', $mensagem);
		
		ini_set('display_errors', 1);
		self::$Banco->requisitar('insert into categoria values(1, "Música", null)')->rebobinar();
	}
	
	public function testErroDeRequisicaoComMensagemEspecifica(){
		$this->setExpectedException('UnexpectedValueException', 'O registro já existe.');
		ini_set('display_errors', 0);
		self::$Banco->requisitar('insert into categoria values(1, "Música", null)')->rebobinar();
	}
	
	public function testErroDeRequisicaoComMensagemGenerica(){
		$this->setExpectedException('UnexpectedValueException', '#1048 Houve um erro ao realizar a requisição.');
		ini_set('display_errors', 0);
		self::$Banco->requisitar('insert into categoria values(1, null, null)')->rebobinar();
	}
	
	public function testGetAfetados(){
		self::$Banco->requisitar('select nome from categoria where id = 1');
		$this->assertSame(1, self::$Banco->getAfetados());
	}
	
	public function testGetId(){
		self::$Banco->requisitar('select nome from categoria where id = 1');
		$this->assertSame(0, self::$Banco->getId());
	}
	
	public function testVetorizar(){
		self::$Banco->requisitar('select nome from categoria where id = 1');
		$c = self::$Banco->vetorizar();
		$this->assertSame('Filme', $c['nome']);
	}
	
	public function testQuantificar(){
		self::$Banco->requisitar('select nome from categoria where id = 1');
		$this->assertSame(1, self::$Banco->quantificar());
	}
	
	public function testMapear(){
		self::$Banco->requisitar('select nome from categoria where id = 1');
		$c = self::$Banco->mapear();
		$this->assertSame(4097, $c[0]->flags);
	}
	
	public static function tearDownAfterClass(){
		ini_set('display_errors', 1);
	}
}
