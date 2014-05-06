<?php
class MitiBDTest extends PHPUnit_Framework_TestCase{
	protected $MitiBD;
	
	protected function setUp(){
		$this->MitiBD=new MitiBD;
		$this->MitiBD->requisitar('select nome from categorias where id=1');
	}
	
	public function testErroConexao(){
		$this->setExpectedException('Exception',"Unknown database 'nao_existe'");
		new MitiBD('localhost','root','root','nao_existe');
	}
	
	public function testErroCharset(){
		$this->setExpectedException(
			'Exception','Houve um erro ao definir o charset'
		);
		
		new MitiBD('localhost','root','root','miti_unit','nao_existe');
	}
	
	public function testEscaparArray(){
		$this->assertSame(
			array("\\'",'\\"','\\\\'),$this->MitiBD->escapar(array("'",'"','\\'))
		);
	}
	
	public function testEscaparString(){
		$this->assertSame('\\\'\\"\\\\',$this->MitiBD->escapar('\'"\\'));
	}
	
	public function testRequisitarPkDuplicada(){
		$this->setExpectedException(
			'Exception',"Duplicate entry '1' for key 'PRIMARY'"
		);
		
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
