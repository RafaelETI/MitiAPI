<?php
class BancoTest extends PHPUnit_Framework_TestCase{
	private $Banco;
	
	protected function setUp(){
		$this->Banco=new \miti\Banco;
		$this->Banco->requisitar('select nome from categoria where id=1');
	}
	
	public function testErroDeConexaoComMensagemTecnica(){
		$this->setExpectedException('Exception',"Unknown database 'nao_existe'");
		
		ini_set('display_errors',1);
		new \miti\Banco(BANCO_SERVIDOR, BANCO_USUARIO, BANCO_SENHA, 'nao_existe');
	}
	
	public function testErroDeConexaoComMensagemGenerica(){
		$mensagem='Não foi possível conectar ao banco de dados.';
		$this->setExpectedException('Exception',$mensagem);
		
		ini_set('display_errors',0);
		new \miti\Banco(BANCO_SERVIDOR, BANCO_USUARIO, BANCO_SENHA, 'nao_existe');
	}
	
	public function testErroDeCharset(){
		$mensagem='Houve um erro ao definir o charset.';
		$this->setExpectedException('Exception',$mensagem);
		
		new \miti\Banco(BANCO_SERVIDOR, BANCO_USUARIO, BANCO_SENHA, BANCO_BANCO, 'nao_existe');
	}
	
	public function testEscaparArray(){
		$especiais=$this->Banco->escapar(array("'",'"','\\'));
		$this->assertSame(array("\\'",'\\"','\\\\'),$especiais);
	}
	
	public function testEscaparString(){
		$this->assertSame('\\\'\\"\\\\',$this->Banco->escapar('\'"\\'));
	}
	
	public function testErroDeRequisicaoComMensagemTecnica(){
		$mensagem=
			"Duplicate entry '1' for key 'PRIMARY'\n\n"
			.'insert into categoria values(1,"Música",null)'
		;
		
		$this->setExpectedException('Exception',$mensagem);
		
		ini_set('display_errors',1);
		$this->Banco->requisitar('insert into categoria values(1,"Música",null)');
	}
	
	public function testErroDeRequisicaoComMensagemGenerica(){
		$mensagem='Houve um erro ao realizar a requisição.';
		$this->setExpectedException('Exception',$mensagem);
		
		ini_set('display_errors',0);
		$this->Banco->requisitar('insert into categoria values(1,"Música",null)');
	}
	
	public function testGetAfetados(){
		$this->assertSame(1,$this->Banco->getAfetados());
	}
	
	public function testGetId(){
		$this->assertSame(0,$this->Banco->getId());
	}
	
	public function testObterAssoc(){
		$categoria=$this->Banco->obterAssoc();
		$this->assertSame('Filme',$categoria['nome']);
	}
	
	public function testObterQuantidade(){
		$this->assertSame(1,$this->Banco->obterQuantidade());
	}
	
	public function testObterCampos(){
		$categoria=$this->Banco->obterCampos();
		$this->assertSame(4097,$categoria[0]->flags);
	}
	
	public static function tearDownAfterClass(){
		ini_set('display_errors',1);
	}
}
