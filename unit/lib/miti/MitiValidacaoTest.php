<?php
require_once 'Config.php'; Config::setInstance();

class MitiValidacaoTest extends PHPUnit_Framework_TestCase{
	protected $MitiValidacao;
	
	protected function setUp(){
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
		$teste='teste';
		$this->MitiValidacao->tamanho($teste,5);
	}
	
	public function testTamanhoException(){
		$this->setExpectedException('Exception','O valor deve conter at� 5 caract�res');
		
		$teste='testes';
		$this->MitiValidacao->tamanho($teste,5);
	}
	
	public function testEmail(){
		$teste='conta@dominio.com';
		$this->MitiValidacao->email($teste);
	}
	
	public function testEmailException(){
		$this->setExpectedException('Exception','O e-mail � inv�lido');
		
		$teste='conta(at)dominio.com';
		$this->MitiValidacao->email($teste);
	}
	
	public function testVazio(){
		$this->vazioArray();
		$this->vazioScalar();
	}
	
	private function vazioArray(){
		$teste=array('a','b','c');
		$this->MitiValidacao->vazio($teste);
	}
	
	public function testVazioArrayException(){
		$this->setExpectedException('Exception','Valor vazio');
		
		$teste=array('a','','c');
		$this->MitiValidacao->vazio($teste);
	}
	
	private function vazioScalar(){
		$teste='a';
		$this->MitiValidacao->vazio($teste);
	}
	
	public function testVazioScalarException(){
		$this->setExpectedException('Exception','Valor vazio');
		
		$teste='';
		$this->MitiValidacao->vazio($teste);
	}
	
	public function testUpload(){
		$this->MitiValidacao->upload('arquivo',2048,array('jpeg','png','gif'));
	}
	
	public function testValidarPesoException(){
		$this->setExpectedException('Exception','O arquivo excede o tamanho permitido');
		$this->MitiValidacao->upload('arquivo',1024,array('jpeg','png','gif'));
	}
	
	public function testValidarTiposException(){
		$this->setExpectedException('Exception','O tipo do arquivo � inv�lido');
		$this->MitiValidacao->upload('arquivo',2048,array('doc','pdf','xls'));
	}
	
	public function testUploadImagem(){
		$this->MitiValidacao->uploadImagem('arquivo',16,16);
	}
	
	public function testValidarTamanhoLarguraException(){
		$this->setExpectedException('Exception','A largura da imagem � menor do que o m�nimo permitido');
		$this->MitiValidacao->uploadImagem('arquivo',20,16);
	}
	
	public function testValidarTamanhoAlturaException(){
		$this->setExpectedException('Exception','A altura da imagem � menor do que o m�nimo permitido');
		$this->MitiValidacao->uploadImagem('arquivo',16,20);
	}
	
	public function testValidarProporcoesVerticalException(){
		$this->setExpectedException('Exception','A propor��o da imagem � inv�lida, excedendo verticalmente');
		$this->MitiValidacao->uploadImagem('arquivo',16,8);
	}
	
	public function testValidarProporcoesHorizontalException(){
		$this->setExpectedException('Exception','A propor��o da imagem � inv�lida, excedendo horizontalmente');
		$this->MitiValidacao->uploadImagem('arquivo',8,16);
	}
	
	public function testCPF(){
		$teste='27981094003';
		$this->MitiValidacao->CPF($teste);
	}
	
	public function testValidarQuantidadeCaracteresException(){
		$teste='279810940033';
		$this->setExpectedException('Exception','#1 - O CPF � inv�lido');
		$this->MitiValidacao->CPF($teste);
	}
	
	public function testValidarApenasNumerosException(){
		$teste='279810a4003';
		$this->setExpectedException('Exception','#2 - O CPF � inv�lido');
		$this->MitiValidacao->CPF($teste);
	}
	
	public function testValidarSequenciaIgualException(){
		$teste='88888888888';
		$this->setExpectedException('Exception','#3 - O CPF � inv�lido');
		$this->MitiValidacao->CPF($teste);
	}
	
	public function testValidarDigitosCPFException(){
		$teste='27981094004';
		$this->setExpectedException('Exception','#4 - O CPF � inv�lido');
		$this->MitiValidacao->CPF($teste);
	}
	
	public function testCNPJ(){
		$teste='87210343000169';
		$this->MitiValidacao->CNPJ($teste);
	}
	
	public function testValidarQuantidadeCaracteresCNPJException(){
		$teste='872103430001699';
		$this->setExpectedException('Exception','#1 - O CNPJ � inv�lido');
		$this->MitiValidacao->CNPJ($teste);
	}
	
	public function testValidarApenasNumerosCNPJException(){
		$teste='87210343a00169';
		$this->setExpectedException('Exception','#2 - O CNPJ � inv�lido');
		$this->MitiValidacao->CNPJ($teste);
	}
	
	public function testValidarSequenciaZerosException(){
		$teste='00000000000000';
		$this->setExpectedException('Exception','#3 - O CNPJ � inv�lido');
		$this->MitiValidacao->CNPJ($teste);
	}
	
	public function testValidarDigitosCNPJException(){
		$teste='87210343000159';
		$this->setExpectedException('Exception','#4 - O CNPJ � inv�lido');
		$this->MitiValidacao->CNPJ($teste);
	}
}