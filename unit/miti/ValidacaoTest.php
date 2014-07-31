<?php
class ValidacaoTest extends PHPUnit_Framework_TestCase{
	public static function setUpBeforeClass(){
		$_FILES['arquivo']['name'][0]='miti.png';
		$_FILES['arquivo']['type'][0]='image/png';
		$_FILES['arquivo']['tmp_name'][0]=RAIZ.'/unit/arquivo/miti.png';
		$_FILES['arquivo']['size'][0]='1457';
		
		$_FILES['arquivo2']['name'][0]='';
	}
	
	public function testTamanhoVazio(){
		$this->assertSame(null,\miti\Validacao::tamanho('',5));
	}
	
	public function testTamanho(){
		\miti\Validacao::tamanho('teste',5);
	}
	
	public function testExcessoTamanho(){
		$this->setExpectedException('Exception','O valor deve conter at� 5 caract�res.');
		\miti\Validacao::tamanho('testes',5);
	}
	
	public function testEmailVazio(){
		$this->assertSame(null,\miti\Validacao::email(''));
	}
	
	public function testEmail(){
		\miti\Validacao::email('conta@dominio.com');
	}
	
	public function testEmailInvalido(){
		$this->setExpectedException('Exception','O e-mail � inv�lido.');
		\miti\Validacao::email('conta(at)dominio.com');
	}
	
	public function testVazioArray(){
		\miti\Validacao::vazio(array('a','b','c'));
	}
	
	public function testVazioArrayComVazio(){
		$this->setExpectedException('Exception','Valor vazio.');
		\miti\Validacao::vazio(array('a','','c'));
	}
	
	public function testVazioScalar(){
		\miti\Validacao::vazio('a');
	}
	
	public function testVazioScalarComVazio(){
		$this->setExpectedException('Exception','Valor vazio.');
		\miti\Validacao::vazio('');
	}
	
	public function testUpload(){
		\miti\Validacao::upload('arquivo',2,array('jpeg','png','gif'));
	}
	
	public function testUploadSemEnvioDeArquivo(){
		$this->assertSame(null,\miti\Validacao::upload('arquivo2',2,array('jpeg')));
	}
	
	public function testUploadComExcessoDePeso(){
		$this->setExpectedException('Exception','O arquivo excede o tamanho permitido.');
		\miti\Validacao::upload('arquivo',1,array('jpeg','png','gif'));
	}
	
	public function testUploadComTipoInvalido(){
		$this->setExpectedException('Exception','O tipo do arquivo � inv�lido.');
		\miti\Validacao::upload('arquivo',2,array('doc','pdf','xls'));
	}
	
	public function testUploadImagem(){
		\miti\Validacao::uploadImagem('arquivo',16,16);
	}
	
	public function testUploadImagemSemEnvioDeArquivo(){
		$this->assertSame(null,\miti\Validacao::uploadImagem('arquivo2',16,16));
	}
	
	public function testUploadImagemComExcessoDeLargura(){
		$this->setExpectedException(
			'Exception','A largura da imagem � menor do que o m�nimo permitido.'
		);
		
		\miti\Validacao::uploadImagem('arquivo',20,16);
	}
	
	public function testUploadImagemComExcessoDeAltura(){
		$this->setExpectedException(
			'Exception','A altura da imagem � menor do que o m�nimo permitido.'
		);
		
		\miti\Validacao::uploadImagem('arquivo',16,20);
	}
	
	public function testUploadImagemComProporcaoEmExcessoNaVertical(){
		$this->setExpectedException(
			'Exception','A propor��o da imagem � inv�lida, excedendo verticalmente.'
		);
		
		\miti\Validacao::uploadImagem('arquivo',16,8);
	}
	
	public function testUploadImagemComProporcaoEmExcessoNaHorizontal(){
		$this->setExpectedException(
			'Exception','A propor��o da imagem � inv�lida, excedendo horizontalmente.'
		);
		
		\miti\Validacao::uploadImagem('arquivo',8,16);
	}
	
	public function testCpfVazio(){
		$this->assertSame(null,\miti\Validacao::cpf(''));
	}
	
	public function testCpf(){
		\miti\Validacao::cpf('27981094003');
	}
	
	public function testCpfComExcessoDeCaracteres(){
		$this->setExpectedException('Exception','#1 O CPF � inv�lido.');
		\miti\Validacao::cpf('279810940033');
	}
	
	public function testCpfComPresencaDeLetra(){
		$this->setExpectedException('Exception','#2 O CPF � inv�lido.');
		\miti\Validacao::cpf('279810a4003');
	}
	
	public function testCpfComSequenciaIgual(){
		$this->setExpectedException('Exception','#3 O CPF � inv�lido.');
		\miti\Validacao::cpf('88888888888');
	}
	
	public function testCpfComDigitoInvalido(){
		$this->setExpectedException('Exception','#4 O CPF � inv�lido.');
		\miti\Validacao::cpf('27981094004');
	}
	
	public function testCnpjVazio(){
		$this->assertSame(null,\miti\Validacao::cnpj(''));
	}
	
	public function testCnpj(){
		\miti\Validacao::cnpj('87210343000169');
	}
	
	public function testCnpjComExcessoDeCaracteres(){
		$this->setExpectedException('Exception','#1 O CNPJ � inv�lido.');
		\miti\Validacao::cnpj('872103430001699');
	}
	
	public function testCnpjComPresencaDeLetra(){
		$this->setExpectedException('Exception','#2 O CNPJ � inv�lido.');
		\miti\Validacao::cnpj('87210343a00169');
	}
	
	public function testCnpjComSequenciaDeZeros(){
		$this->setExpectedException('Exception','#3 O CNPJ � inv�lido.');
		\miti\Validacao::cnpj('00000000000000');
	}
	
	public function testCnpjComDigitoInvalido(){
		$this->setExpectedException('Exception','#4 O CNPJ � inv�lido.');
		\miti\Validacao::cnpj('87210343000159');
	}
	
	public function testCnpjComDigitoInvalidoComZero(){
		$this->setExpectedException('Exception','#4 O CNPJ � inv�lido.');
		\miti\Validacao::cnpj('80911582000106');
	}
}
