<?php
class MitiBDTest extends PHPUnit_Framework_TestCase{
	private $MitiBD;
	
	protected function setUp(){
		$this->MitiBD=new MitiBD;
		
		//sleep colocado para que o testGetTempo seja bem sucedido
		$this->MitiBD->requisitar('select nome,sleep(0.001) from categoria where id=1');
	}
	
	public function testErroDeConexaoComMensagemTecnica(){
		$this->setExpectedException('Exception',"Unknown database 'nao_existe'");
		
		ini_set('display_errors',1);
		new MitiBD('localhost','root','root','nao_existe');
	}
	
	public function testErroDeConexaoComMensagemGenerica(){
		$mensagem='Não foi possível conectar ao banco de dados.';
		$this->setExpectedException('Exception',$mensagem);
		
		ini_set('display_errors',0);
		new MitiBD('localhost','root','root','nao_existe');
	}
	
	public function testErroDeCharset(){
		$mensagem='Houve um erro ao definir o charset.';
		$this->setExpectedException('Exception',$mensagem);
		
		new MitiBD('localhost','root','root','miti_unit','nao_existe');
	}
	
	public function testEscaparArray(){
		$especiais=$this->MitiBD->escapar(array("'",'"','\\'));
		$this->assertSame(array("\\'",'\\"','\\\\'),$especiais);
	}
	
	public function testEscaparString(){
		$this->assertSame('\\\'\\"\\\\',$this->MitiBD->escapar('\'"\\'));
	}
	
	public function testErroDeRequisicaoComMensagemTecnica(){
		$mensagem=
			"Duplicate entry '1' for key 'PRIMARY'\n\n"
			.'insert into categoria values(1,"Música",null)'
		;
		
		$this->setExpectedException('Exception',$mensagem);
		
		ini_set('display_errors',1);
		$this->MitiBD->requisitar('insert into categoria values(1,"Música",null)');
	}
	
	public function testErroDeRequisicaoComMensagemGenerica(){
		$mensagem='Houve um erro ao realizar a requisição.';
		$this->setExpectedException('Exception',$mensagem);
		
		ini_set('display_errors',0);
		$this->MitiBD->requisitar('insert into categoria values(1,"Música",null)');
	}
	
	public function testGetTempo(){
		$this->assertGreaterThan(0,$this->MitiBD->getTempo());
	}
	
	public function testGetAfetados(){
		$this->assertSame(1,$this->MitiBD->getAfetados());
	}
	
	public function testGetId(){
		$this->assertSame(0,$this->MitiBD->getId());
	}
	
	public function testObterAssoc(){
		$categoria=$this->MitiBD->obterAssoc();
		$this->assertSame('Filme',$categoria['nome']);
	}
	
	public function testObterQuantidade(){
		$this->assertSame(1,$this->MitiBD->obterQuantidade());
	}
	
	public function testObterCampos(){
		$categoria=$this->MitiBD->obterCampos();
		$this->assertSame(4097,$categoria[0]->flags);
	}
	
	public static function tearDownAfterClass(){
		ini_set('display_errors',1);
	}
}
