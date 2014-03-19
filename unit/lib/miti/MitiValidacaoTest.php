<?php
class MitiValidacaoTest extends PHPUnit_Framework_TestCase{
	protected $MitiValidacao;
	
	protected function setUp(){
		require_once 'Config.php';
		Config::setInstance();
		
		$this->MitiValidacao=new MitiValidacao;
		$this->declararFiles();
	}
	
	private function declararFiles(){
		$_FILES['arquivo']['name'][0]='mitiunit.png';
		$_FILES['arquivo']['type'][0]='image/png';
		$_FILES['arquivo']['tmp_name'][0]=RAIZ.'img/mitiunit.png';
		$_FILES['arquivo']['size'][0]='1457';
	}
	
	public function testTamanho(){
		$this->MitiValidacao->tamanho('teste',5);
		$this->assertSame(null,$this->MitiValidacao->tamanho('',5));
	}
	
	public function testTamanhoException(){
		$this->setExpectedException('Exception','O valor deve conter até 5 caractéres');
		$this->MitiValidacao->tamanho('testes',5);
	}
	
	public function testEmail(){
		$this->MitiValidacao->email('conta@dominio.com');
		$this->assertSame(null,$this->MitiValidacao->email(''));
	}
	
	public function testEmailException(){
		$this->setExpectedException('Exception','O e-mail é inválido');
		$this->MitiValidacao->email('conta(at)dominio.com');
	}
	
	public function testVazio(){
		$this->vazioArray();
		$this->vazioScalar();
	}
	
	private function vazioArray(){
		$this->MitiValidacao->vazio(array('a','b','c'));
	}
	
	public function testVazioArrayException(){
		$this->setExpectedException('Exception','Valor vazio');
		$this->MitiValidacao->vazio(array('a','','c'));
	}
	
	private function vazioScalar(){
		$this->MitiValidacao->vazio('a');
	}
	
	public function testVazioScalarException(){
		$this->setExpectedException('Exception','Valor vazio');
		$this->MitiValidacao->vazio('');
	}
	
	public function testUpload(){
		$this->MitiValidacao->upload('arquivo',2048,array('jpeg','png','gif'));
		
		$teste=$this->MitiValidacao->upload('nao_existe',2048,array('jpeg','png','gif'));
		$this->assertSame(null,$teste);
	}
	
	public function testValidarPesoException(){
		$this->setExpectedException('Exception','O arquivo excede o tamanho permitido');
		$this->MitiValidacao->upload('arquivo',1024,array('jpeg','png','gif'));
	}
	
	public function testValidarTiposException(){
		$this->setExpectedException('Exception','O tipo do arquivo é inválido');
		$this->MitiValidacao->upload('arquivo',2048,array('doc','pdf','xls'));
	}
	
	public function testUploadImagem(){
		$this->MitiValidacao->uploadImagem('arquivo',16,16);
		
		$teste=$this->MitiValidacao->uploadImagem('nao_existe',16,16);
		$this->assertSame(null,$teste);
	}
	
	public function testValidarTamanhoLarguraException(){
		$this->setExpectedException('Exception','A largura da imagem é menor do que o mínimo permitido');
		$this->MitiValidacao->uploadImagem('arquivo',20,16);
	}
	
	public function testValidarTamanhoAlturaException(){
		$this->setExpectedException('Exception','A altura da imagem é menor do que o mínimo permitido');
		$this->MitiValidacao->uploadImagem('arquivo',16,20);
	}
	
	public function testValidarProporcoesVerticalException(){
		$this->setExpectedException('Exception','A proporção da imagem é inválida, excedendo verticalmente');
		$this->MitiValidacao->uploadImagem('arquivo',16,8);
	}
	
	public function testValidarProporcoesHorizontalException(){
		$this->setExpectedException('Exception','A proporção da imagem é inválida, excedendo horizontalmente');
		$this->MitiValidacao->uploadImagem('arquivo',8,16);
	}
	
	public function testCPF(){
		$this->MitiValidacao->CPF('27981094003');
		$this->assertSame(null,$this->MitiValidacao->CPF(''));
	}
	
	public function testValidarQuantidadeCaracteresException(){
		$this->setExpectedException('Exception','#1 - O CPF é inválido');
		$this->MitiValidacao->CPF('279810940033');
	}
	
	public function testValidarApenasNumerosException(){
		$this->setExpectedException('Exception','#2 - O CPF é inválido');
		$this->MitiValidacao->CPF('279810a4003');
	}
	
	public function testValidarSequenciaIgualException(){
		$this->setExpectedException('Exception','#3 - O CPF é inválido');
		$this->MitiValidacao->CPF('88888888888');
	}
	
	public function testValidarDigitosCPFException(){
		$this->setExpectedException('Exception','#4 - O CPF é inválido');
		$this->MitiValidacao->CPF('27981094004');
	}
	
	public function testCNPJ(){
		$this->MitiValidacao->CNPJ('87210343000169');
		$this->assertSame(null,$this->MitiValidacao->CNPJ(''));
	}
	
	public function testValidarQuantidadeCaracteresCNPJException(){
		$this->setExpectedException('Exception','#1 - O CNPJ é inválido');
		$this->MitiValidacao->CNPJ('872103430001699');
	}
	
	public function testValidarApenasNumerosCNPJException(){
		$this->setExpectedException('Exception','#2 - O CNPJ é inválido');
		$this->MitiValidacao->CNPJ('87210343a00169');
	}
	
	public function testValidarSequenciaZerosException(){
		$this->setExpectedException('Exception','#3 - O CNPJ é inválido');
		$this->MitiValidacao->CNPJ('00000000000000');
	}
	
	public function testValidarDigitosCNPJException(){
		$this->setExpectedException('Exception','#4 - O CNPJ é inválido');
		$this->MitiValidacao->CNPJ('87210343000159');
	}
	
	public function testValidarDigitosComZeroCNPJException(){
		$this->setExpectedException('Exception','#4 - O CNPJ é inválido');
		$this->MitiValidacao->CNPJ('80911582000106');
	}
}