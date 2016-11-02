<?php
use PHPUnit\Framework\TestCase;

class BancoTest extends TestCase{
	private static $config;
	private $banco;
	
	public static function setUpBeforeClass(){
		global $config;
		self::$config = $config;
	}
	
    protected function setUp(){
        $this->banco = new \Miti\Banco(self::$config);
    }
    
    protected function tearDown(){
        $this->banco->rebobinar();
        unset($this->banco);
    }
    
	public function testErroConexaoMensagemTecnica(){
		$this->setExpectedException('RuntimeException', "Unknown database 'nao_existe'");
		
		ini_set('display_errors', 1);
		$config = self::$config;
		$config['banco']['nome'] = 'nao_existe';
		new \Miti\Banco($config);
	}
	
	public function testErroConexaoMensagemGenerica(){
		$this->setExpectedException('RuntimeException', 'Não foi possível conectar ao banco de dados.');
		
		ini_set('display_errors', 0);
		$config = self::$config;
		$config['banco']['nome'] = 'nao_existe';
		new \Miti\Banco($config);
	}
	
	public function testErroCharset(){
		$mensagem = 'Houve um erro ao definir o charset.';
		$this->setExpectedException('DomainException', $mensagem);
		
		$config = self::$config;
		$config['banco']['charset'] = 'nao_existe';
		new \Miti\Banco($config);
	}
	
	public function testEscaparArray(){
		$this->assertSame(["\\'", '\\"', '\\\\'], $this->banco->escapar(["'", '"', '\\']));
	}
	
	public function testEscaparString(){
		$this->assertSame('\\\'\\"\\\\', $this->banco->escapar('\'"\\'));
	}
	
	public function testErroRequisicaoMensagemTecnica(){
		$mensagem =
			"#1062 Duplicate entry '1' for key 'PRIMARY' - "
			.'insert into categoria values(1, "Música", null)'
		;
		
		$this->setExpectedException('UnexpectedValueException', $mensagem);
		
		ini_set('display_errors', 1);
        
		$this->banco->requisitar('insert into categoria values(1, "Música", null)');
	}
	
	public function testErroRequisicaoMensagemEspecifica(){
		$this->setExpectedException('UnexpectedValueException', 'O registro já existe.');
		ini_set('display_errors', 0);
		$this->banco->requisitar('insert into categoria values(1, "Música", null)');
	}
	
	public function testErroRequisicaoMensagemGenerica(){
		$this->setExpectedException('UnexpectedValueException', '#1048 Houve um erro ao realizar a requisição.');
		ini_set('display_errors', 0);
		$this->banco->requisitar('insert into categoria values(1, null, null)');
	}
	
	public function testGetAfetados(){
		$this->assertSame(1, $this->banco->requisitar('select nome from categoria where id = 1')->getAfetados());
	}
	
	public function testGetId(){
		$this->assertSame(0, $this->banco->requisitar('select nome from categoria where id = 1')->getId());
	}
	
	public function testVetorizar(){
		$this->assertSame('Filme', $this->banco->requisitar('select nome from categoria where id = 1')->vetorizar()['nome']);
	}
	
	public function testQuantificar(){
		$this->assertSame(1, $this->banco->requisitar('select nome from categoria where id = 1')->quantificar());
	}
	
	public function testMapear(){
		$this->assertSame(4097, $this->banco->requisitar('select nome from categoria where id = 1')->mapear()[0]->flags);
	}
	
	public static function tearDownAfterClass(){
		ini_set('display_errors', 1);
	}
}
