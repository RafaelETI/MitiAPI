<?php
class ValidacaoTest extends PHPUnit_Framework_TestCase{
	private static $arquivos=array();
	private static $arquivos2=array();
	
	public static function setUpBeforeClass(){
		self::$arquivos['name'][0]='miti.png';
		self::$arquivos['type'][0]='image/png';
		self::$arquivos['tmp_name'][0]=RAIZ.'/unit/arquivo/miti.png';
		self::$arquivos['size'][0]='1457';
		
		self::$arquivos2['name'][0]='';
	}
	
	public function testTamanhoVazio(){
		$this->assertSame(null,\miti\Validacao::tamanho('',5));
	}
	
	public function testTamanho(){
		\miti\Validacao::tamanho('teste',5);
	}
	
	public function testExcessoTamanho(){
		$this->setExpectedException('Exception','O valor deve conter até 5 caractéres.');
		\miti\Validacao::tamanho('testes',5);
	}
	
	public function testEmailVazio(){
		$this->assertSame(null,\miti\Validacao::email(''));
	}
	
	public function testEmail(){
		\miti\Validacao::email('conta@dominio.com');
	}
	
	public function testEmailInvalido(){
		$this->setExpectedException('Exception','O e-mail é inválido.');
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
		\miti\Validacao::arquivo(self::$arquivos,2,array('jpeg','png','gif'));
	}
	
	public function testUploadSemEnvioDeArquivo(){
		$this->assertSame(null,\miti\Validacao::arquivo(self::$arquivos2,2,array('jpeg')));
	}
	
	public function testUploadComExcessoDePeso(){
		$this->setExpectedException('Exception','O arquivo excede o tamanho permitido.');
		\miti\Validacao::arquivo(self::$arquivos,1,array('jpeg','png','gif'));
	}
	
	public function testUploadComTipoInvalido(){
		$this->setExpectedException('Exception','O tipo do arquivo é inválido.');
		\miti\Validacao::arquivo(self::$arquivos,2,array('doc','pdf','xls'));
	}
	
	public function testUploadImagem(){
		\miti\Validacao::imagem(self::$arquivos,16,16);
	}
	
	public function testUploadImagemSemEnvioDeArquivo(){
		$this->assertSame(null,\miti\Validacao::imagem(self::$arquivos2,16,16));
	}
	
	public function testUploadImagemComExcessoDeLargura(){
		$mensagem='A largura da imagem é menor do que o mínimo permitido.';
		$this->setExpectedException('Exception',$mensagem);
		\miti\Validacao::imagem(self::$arquivos,20,16);
	}
	
	public function testUploadImagemComExcessoDeAltura(){
		$mensagem='A altura da imagem é menor do que o mínimo permitido.';
		$this->setExpectedException('Exception',$mensagem);
		\miti\Validacao::imagem(self::$arquivos,16,20);
	}
	
	public function testUploadImagemComProporcaoEmExcessoNaVertical(){
		$mensagem='A proporção da imagem é inválida, excedendo verticalmente.';
		$this->setExpectedException('Exception',$mensagem);
		\miti\Validacao::imagem(self::$arquivos,16,8);
	}
	
	public function testUploadImagemComProporcaoEmExcessoNaHorizontal(){
		$mensagem='A proporção da imagem é inválida, excedendo horizontalmente.';
		$this->setExpectedException('Exception',$mensagem);
		\miti\Validacao::imagem(self::$arquivos,8,16);
	}
	
	public function testCpfVazio(){
		$this->assertSame(null,\miti\Validacao::cpf(''));
	}
	
	public function testCpf(){
		\miti\Validacao::cpf('27981094003');
	}
	
	public function testCpfComExcessoDeCaracteres(){
		$this->setExpectedException('Exception','#1 O CPF é inválido.');
		\miti\Validacao::cpf('279810940033');
	}
	
	public function testCpfComPresencaDeLetra(){
		$this->setExpectedException('Exception','#2 O CPF é inválido.');
		\miti\Validacao::cpf('279810a4003');
	}
	
	public function testCpfComSequenciaIgual(){
		$this->setExpectedException('Exception','#3 O CPF é inválido.');
		\miti\Validacao::cpf('88888888888');
	}
	
	public function testCpfComDigitoInvalido(){
		$this->setExpectedException('Exception','#4 O CPF é inválido.');
		\miti\Validacao::cpf('27981094004');
	}
	
	public function testCnpjVazio(){
		$this->assertSame(null,\miti\Validacao::cnpj(''));
	}
	
	public function testCnpj(){
		\miti\Validacao::cnpj('87210343000169');
	}
	
	public function testCnpjComExcessoDeCaracteres(){
		$this->setExpectedException('Exception','#1 O CNPJ é inválido.');
		\miti\Validacao::cnpj('872103430001699');
	}
	
	public function testCnpjComPresencaDeLetra(){
		$this->setExpectedException('Exception','#2 O CNPJ é inválido.');
		\miti\Validacao::cnpj('87210343a00169');
	}
	
	public function testCnpjComSequenciaDeZeros(){
		$this->setExpectedException('Exception','#3 O CNPJ é inválido.');
		\miti\Validacao::cnpj('00000000000000');
	}
	
	public function testCnpjComDigitoInvalido(){
		$this->setExpectedException('Exception','#4 O CNPJ é inválido.');
		\miti\Validacao::cnpj('87210343000159');
	}
	
	public function testCnpjComDigitoInvalidoComZero(){
		$this->setExpectedException('Exception','#4 O CNPJ é inválido.');
		\miti\Validacao::cnpj('80911582000106');
	}
}
