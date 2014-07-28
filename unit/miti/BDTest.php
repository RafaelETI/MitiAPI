<?php
class BDTest extends PHPUnit_Framework_TestCase{
	private $BD;
	
	protected function setUp(){
		$this->BD=new Miti\BD;
		
		//sleep colocado para que o testGetTempo possa ser bem sucedido
		$this->BD->requisitar('select nome,sleep(0.001) from categoria where id=1');
	}
	
	public function testErroDeConexaoComMensagemTecnica(){
		$this->setExpectedException('Exception',"Unknown database 'nao_existe'");
		
		ini_set('display_errors',1);
		new Miti\BD('localhost','root','root','nao_existe');
	}
	
	public function testErroDeConexaoComMensagemGenerica(){
		$mensagem='Não foi possível conectar ao banco de dados.';
		$this->setExpectedException('Exception',$mensagem);
		
		ini_set('display_errors',0);
		new Miti\BD('localhost','root','root','nao_existe');
	}
	
	public function testErroDeCharset(){
		$mensagem='Houve um erro ao definir o charset.';
		$this->setExpectedException('Exception',$mensagem);
		
		new Miti\BD('localhost','root','root','miti_unit','nao_existe');
	}
	
	public function testEscaparArray(){
		$especiais=$this->BD->escapar(array("'",'"','\\'));
		$this->assertSame(array("\\'",'\\"','\\\\'),$especiais);
	}
	
	public function testEscaparString(){
		$this->assertSame('\\\'\\"\\\\',$this->BD->escapar('\'"\\'));
	}
	
	public function testErroDeRequisicaoComMensagemTecnica(){
		$mensagem=
			"Duplicate entry '1' for key 'PRIMARY'\n\n"
			.'insert into categoria values(1,"Música",null)'
		;
		
		$this->setExpectedException('Exception',$mensagem);
		
		ini_set('display_errors',1);
		$this->BD->requisitar('insert into categoria values(1,"Música",null)');
	}
	
	public function testErroDeRequisicaoComMensagemGenerica(){
		$mensagem='Houve um erro ao realizar a requisição.';
		$this->setExpectedException('Exception',$mensagem);
		
		ini_set('display_errors',0);
		$this->BD->requisitar('insert into categoria values(1,"Música",null)');
	}
	
	public function testGetTempo(){
		$this->assertGreaterThan(0,$this->BD->getTempo());
	}
	
	public function testGetAfetados(){
		$this->assertSame(1,$this->BD->getAfetados());
	}
	
	public function testGetId(){
		$this->assertSame(0,$this->BD->getId());
	}
	
	public function testObterAssoc(){
		$categoria=$this->BD->obterAssoc();
		$this->assertSame('Filme',$categoria['nome']);
	}
	
	public function testObterQuantidade(){
		$this->assertSame(1,$this->BD->obterQuantidade());
	}
	
	public function testObterCampos(){
		$categoria=$this->BD->obterCampos();
		$this->assertSame(4097,$categoria[0]->flags);
	}
	
	public static function tearDownAfterClass(){
		ini_set('display_errors',1);
	}
}
