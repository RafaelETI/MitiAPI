<?php
class MitiValidacaoTest extends PHPUnit_Framework_TestCase{
	private $MitiValidacao;
	
	protected function setUp(){
		$this->MitiValidacao=new MitiValidacao;
		$this->declararFiles();
	}
	
	private function declararFiles(){
		$_FILES['arquivo']['name'][0]='miti.png';
		$_FILES['arquivo']['type'][0]='image/png';
		$_FILES['arquivo']['tmp_name'][0]=RAIZ.'unit/arquivo/miti.png';
		$_FILES['arquivo']['size'][0]='1457';
		
		$_FILES['arquivo2']['name'][0]='';
	}
	
	public function testTamanhoVazio(){
		$this->assertSame(null,$this->MitiValidacao->tamanho('',5));
	}
	
	public function testTamanho(){
		$this->MitiValidacao->tamanho('teste',5);
	}
	
	public function testExcessoTamanho(){
		$this->setExpectedException(
			'Exception','O valor deve conter até 5 caractéres'
		);
		
		$this->MitiValidacao->tamanho('testes',5);
	}
	
	public function testEmailVazio(){
		$this->assertSame(null,$this->MitiValidacao->email(''));
	}
	
	public function testEmail(){
		$this->MitiValidacao->email('conta@dominio.com');
	}
	
	public function testEmailInvalido(){
		$this->setExpectedException('Exception','O e-mail é inválido');
		$this->MitiValidacao->email('conta(at)dominio.com');
	}
	
	public function testVazioArray(){
		$this->MitiValidacao->vazio(array('a','b','c'));
	}
	
	public function testVazioArrayComVazio(){
		$this->setExpectedException('Exception','Valor vazio');
		$this->MitiValidacao->vazio(array('a','','c'));
	}
	
	public function testVazioScalar(){
		$this->MitiValidacao->vazio('a');
	}
	
	public function testVazioScalarComVazio(){
		$this->setExpectedException('Exception','Valor vazio');
		$this->MitiValidacao->vazio('');
	}
	
	public function testUpload(){
		$this->MitiValidacao->upload('arquivo',2,array('jpeg','png','gif'));
	}
	
	public function testUploadSemEnvioDeArquivo(){
		$this->assertSame(
			null,
			$this->MitiValidacao->upload('arquivo2',2,array('jpeg'))
		);
	}
	
	public function testUploadExcessoPeso(){
		$this->setExpectedException(
			'Exception','O arquivo excede o tamanho permitido'
		);
		
		$this->MitiValidacao->upload('arquivo',1,array('jpeg','png','gif'));
	}
	
	public function testUploadTipoInvalido(){
		$this->setExpectedException('Exception','O tipo do arquivo é inválido');
		$this->MitiValidacao->upload('arquivo',2,array('doc','pdf','xls'));
	}
	
	public function testUploadImagem(){
		$this->MitiValidacao->uploadImagem('arquivo',16,16);
	}
	
	public function testUploadImagemSemEnvioDeArquivo(){
		$this->assertSame(
			null,
			$this->MitiValidacao->uploadImagem('arquivo2',16,16)
		);
	}
	
	public function testUploadImagemExcessoLargura(){
		$this->setExpectedException(
			'Exception','A largura da imagem é menor do que o mínimo permitido'
		);
		
		$this->MitiValidacao->uploadImagem('arquivo',20,16);
	}
	
	public function testUploadImagemExcessoAltura(){
		$this->setExpectedException(
			'Exception','A altura da imagem é menor do que o mínimo permitido'
		);
		
		$this->MitiValidacao->uploadImagem('arquivo',16,20);
	}
	
	public function testUploadImagemProporcaoExcessoVertical(){
		$this->setExpectedException(
			'Exception',
			'A proporção da imagem é inválida, excedendo verticalmente'
		);
		
		$this->MitiValidacao->uploadImagem('arquivo',16,8);
	}
	
	public function testUploadImagemProporcaoExcessoHorizontal(){
		$this->setExpectedException(
			'Exception',
			'A proporção da imagem é inválida, excedendo horizontalmente'
		);
		
		$this->MitiValidacao->uploadImagem('arquivo',8,16);
	}
	
	public function testCpfVazio(){
		$this->assertSame(null,$this->MitiValidacao->cpf(''));
	}
	
	public function testCpf(){
		$this->MitiValidacao->cpf('27981094003');
	}
	
	public function testCpfExcessoCaracteres(){
		$this->setExpectedException('Exception','#1 O CPF é inválido');
		$this->MitiValidacao->cpf('279810940033');
	}
	
	public function testCpfPresencaLetra(){
		$this->setExpectedException('Exception','#2 O CPF é inválido');
		$this->MitiValidacao->cpf('279810a4003');
	}
	
	public function testCpfSequenciaIgual(){
		$this->setExpectedException('Exception','#3 O CPF é inválido');
		$this->MitiValidacao->cpf('88888888888');
	}
	
	public function testCpfDigitoInvalido(){
		$this->setExpectedException('Exception','#4 O CPF é inválido');
		$this->MitiValidacao->cpf('27981094004');
	}
	
	public function testCnpjVazio(){
		$this->assertSame(null,$this->MitiValidacao->cnpj(''));
	}
	
	public function testCnpj(){
		$this->MitiValidacao->cnpj('87210343000169');
	}
	
	public function testCnpjExcessoCaracteres(){
		$this->setExpectedException('Exception','#1 O CNPJ é inválido');
		$this->MitiValidacao->cnpj('872103430001699');
	}
	
	public function testCnpjPresencaLetra(){
		$this->setExpectedException('Exception','#2 O CNPJ é inválido');
		$this->MitiValidacao->cnpj('87210343a00169');
	}
	
	public function testCnpjSequenciaZeros(){
		$this->setExpectedException('Exception','#3 O CNPJ é inválido');
		$this->MitiValidacao->cnpj('00000000000000');
	}
	
	public function testCnpjDigitoInvalido(){
		$this->setExpectedException('Exception','#4 O CNPJ é inválido');
		$this->MitiValidacao->cnpj('87210343000159');
	}
	
	public function testCnpjDigitoInvalidoComZero(){
		$this->setExpectedException('Exception','#4 O CNPJ é inválido');
		$this->MitiValidacao->cnpj('80911582000106');
	}
}
