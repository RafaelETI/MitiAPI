<?php
class ValidacaoTest extends PHPUnit_Framework_TestCase{
	private static $arquivos = array();
	
	public static function setUpBeforeClass(){
		self::$arquivos['caminho'] = '../tests/arquivos/miti.png';
		self::$arquivos['tipo'] = 'image/png';
		self::$arquivos['peso'] = '1457';
	}
	
	public function testTamanhoVazio(){
		$this->assertSame(null, \Miti\Validacao::tamanho('', 5));
	}
	
	public function testTamanho(){
		\Miti\Validacao::tamanho('teste', 5);
	}
	
	public function testExcessoTamanho(){
		$this->setExpectedException('UnexpectedValueException', 'O valor deve conter até 5 caractéres.');
		\Miti\Validacao::tamanho('testes', 5);
	}
	
	public function testEmailVazio(){
		$this->assertSame(null, \Miti\Validacao::email(''));
	}
	
	public function testEmail(){
		\Miti\Validacao::email('conta@dominio.com');
	}
	
	public function testEmailInvalido(){
		$this->setExpectedException('UnexpectedValueException', 'O e-mail é inválido.');
		\Miti\Validacao::email('conta(at)dominio.com');
	}
	
	public function testPeso(){
		\Miti\Validacao::peso(self::$arquivos['peso'], 1500);
	}
	
	public function testPesoExcedido(){
		$this->setExpectedException('UnexpectedValueException', 'O arquivo excede o peso permitido.');
		\Miti\Validacao::peso(self::$arquivos['peso'], 1);
	}
	
	public function testTipo(){
		\Miti\Validacao::tipos(self::$arquivos['tipo'], array('doc', 'png', 'xls'));
	}
	
	public function testTipoInvalido(){
		$this->setExpectedException('RangeException', 'O tipo do arquivo é inválido.');
		\Miti\Validacao::tipos(self::$arquivos['tipo'], array('doc', 'pdf', 'xls'));
	}
	
	public function testImagem(){
		\Miti\Validacao::imagem(self::$arquivos['caminho'], 16, 16);
	}
	
	public function testImagemComExcessoDeLargura(){
		$mensagem = 'A largura da imagem é menor do que o mínimo permitido.';
		$this->setExpectedException('UnexpectedValueException', $mensagem);
		\Miti\Validacao::imagem(self::$arquivos['caminho'], 20, 16);
	}
	
	public function testImagemComExcessoDeAltura(){
		$mensagem = 'A altura da imagem é menor do que o mínimo permitido.';
		$this->setExpectedException('UnexpectedValueException', $mensagem);
		\Miti\Validacao::imagem(self::$arquivos['caminho'], 16, 20);
	}
	
	public function testImagemComProporcaoEmExcessoNaVertical(){
		$mensagem = 'A proporção da imagem é inválida, excedendo verticalmente.';
		$this->setExpectedException('UnexpectedValueException', $mensagem);
		\Miti\Validacao::imagem(self::$arquivos['caminho'], 16, 8);
	}
	
	public function testImagemComProporcaoEmExcessoNaHorizontal(){
		$mensagem = 'A proporção da imagem é inválida, excedendo horizontalmente.';
		$this->setExpectedException('UnexpectedValueException', $mensagem);
		\Miti\Validacao::imagem(self::$arquivos['caminho'], 8, 16);
	}
	
	public function testCpfVazio(){
		$this->assertSame(null, \Miti\Validacao::cpf(''));
	}
	
	public function testCpf(){
		\Miti\Validacao::cpf('27981094003');
	}
	
	public function testCpfComExcessoDeCaracteres(){
		$this->setExpectedException('UnexpectedValueException', '#1 O CPF é inválido.');
		\Miti\Validacao::cpf('279810940033');
	}
	
	public function testCpfComPresencaDeLetra(){
		$this->setExpectedException('UnexpectedValueException', '#2 O CPF é inválido.');
		\Miti\Validacao::cpf('279810a4003');
	}
	
	public function testCpfComSequenciaIgual(){
		$this->setExpectedException('UnexpectedValueException', '#3 O CPF é inválido.');
		\Miti\Validacao::cpf('88888888888');
	}
	
	public function testCpfComDigitoInvalido(){
		$this->setExpectedException('RangeException', '#4 O CPF é inválido.');
		\Miti\Validacao::cpf('27981094004');
	}
	
	public function testCnpjVazio(){
		$this->assertSame(null, \Miti\Validacao::cnpj(''));
	}
	
	public function testCnpj(){
		\Miti\Validacao::cnpj('87210343000169');
	}
	
	public function testCnpjComExcessoDeCaracteres(){
		$this->setExpectedException('UnexpectedValueException', '#1 O CNPJ é inválido.');
		\Miti\Validacao::cnpj('872103430001699');
	}
	
	public function testCnpjComPresencaDeLetra(){
		$this->setExpectedException('UnexpectedValueException', '#2 O CNPJ é inválido.');
		\Miti\Validacao::cnpj('87210343a00169');
	}
	
	public function testCnpjComSequenciaDeZeros(){
		$this->setExpectedException('UnexpectedValueException', '#3 O CNPJ é inválido.');
		\Miti\Validacao::cnpj('00000000000000');
	}
	
	public function testCnpjComDigitoInvalido(){
		$this->setExpectedException('RangeException', '#4 O CNPJ é inválido.');
		\Miti\Validacao::cnpj('87210343000159');
	}
	
	public function testCnpjComDigitoInvalidoComZero(){
		$this->setExpectedException('RangeException', '#4 O CNPJ é inválido.');
		\Miti\Validacao::cnpj('80911582000106');
	}
}
