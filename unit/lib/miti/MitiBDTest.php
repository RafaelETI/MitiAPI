<?php
class MitiBDTest extends PHPUnit_Framework_TestCase{
	protected $MitiBD;
	
	protected function setUp(){
		$this->MitiBD=MitiBD::getInstance();
		$this->MitiBD->requisitar('select nome from categorias where id=1');
	}
	
	public function testEscaparArray(){
		$teste=$this->MitiBD->escapar(array("'",'"','\\'));
		$this->assertSame(array("\\'",'\\"','\\\\'),$teste);
	}
	
	public function testEscaparString(){
		$teste=$this->MitiBD->escapar('\'"\\');
		$this->assertSame('\\\'\\"\\\\',$teste);
	}
	
	public function testRequisitarPkDuplicada(){
		$mensagem="Duplicate entry '1' for key 'PRIMARY'";
		$this->setExpectedException('Exception',$mensagem);
		
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