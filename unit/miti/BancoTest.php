<?php
class BancoTest extends PHPUnit_Framework_TestCase{
	private static $Banco;
	
	public static function setUpBeforeClass(){
		self::$Banco = new \miti\Banco;
	}
	
	public function testErroDeConexaoComMensagemTecnica(){
		$this->setExpectedException('RuntimeException', "Unknown database 'nao_existe'");
		
		ini_set('display_errors', 1);
		new \miti\Banco(CFG_BANCO_SERVIDOR, CFG_BANCO_USUARIO, CFG_BANCO_SENHA, 'nao_existe');
	}
	
	public function testErroDeConexaoComMensagemGenerica(){
		$mensagem = 'Não foi possível conectar ao banco de dados.';
		$this->setExpectedException('RuntimeException', $mensagem);
		
		ini_set('display_errors', 0);
		new \miti\Banco(CFG_BANCO_SERVIDOR, CFG_BANCO_USUARIO, CFG_BANCO_SENHA, 'nao_existe');
	}
	
	public function testErroDeCharset(){
		$mensagem = 'Houve um erro ao definir o charset.';
		$this->setExpectedException('DomainException', $mensagem);
		
		new \miti\Banco(CFG_BANCO_SERVIDOR, CFG_BANCO_USUARIO, CFG_BANCO_SENHA, CFG_BANCO_NOME, 'nao_existe');
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
			"Duplicate entry '1' for key 'PRIMARY' - "
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
		$this->setExpectedException('UnexpectedValueException', 'Houve um erro ao realizar a requisição.');
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
