<?php
class MitiBDTest extends PHPUnit_Framework_TestCase{
	protected $MitiBD;
	
	protected function setUp(){
		require_once 'Config.php';
		Config::setInstance();
		
		$this->MitiBD=new MitiBD;
		$this->MitiBD->requisitar('select nome from categorias where id=1');
	}
	
	public function testVerificarErroConexaoException(){
		$this->setExpectedException('Exception','Não foi possível conectar ao banco de dados');
		$this->MitiBD=@new MitiBD('localhost','root','root','nao_existe');
	}
	
	public function testVerificarErroCharsetException(){
		$this->setExpectedException('Exception','Houve um erro ao definir o charset');
		$this->MitiBD=new MitiBD('localhost','root','root','miti_unit','nao_existe');
	}
	
	public function testEscapar(){
		$this->escaparArray();
		$this->escaparString();
	}
	
	private function escaparArray(){
		$teste=array("'",'"','\\');
		$this->MitiBD->escapar($teste);
		$this->assertSame(array("\\'",'\\"','\\\\'),$teste);
	}
	
	private function escaparString(){
		$teste='\'"\\';
		$this->MitiBD->escapar($teste);
		$this->assertSame('\\\'\\"\\\\',$teste);
	}
	
	public function testRequisitarException(){
		$this->setExpectedException('Exception','Houve um erro ao realizar a requisição');
		$this->MitiBD->requisitar('insert into categorias values(1,"Música",null)');
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
		$teste=$this->MitiBD->obterAssoc();
		$this->assertSame('Filme',$teste['nome']);
	}
	
	public function testObterQuantidade(){
		$this->assertSame(1,$this->MitiBD->obterQuantidade());
	}
	
	public function testObterCampos(){
		$teste=$this->MitiBD->obterCampos();
		$this->assertSame(4097,$teste[0]->flags);
	}
}