<?php
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
		$this->MitiValidacao->tamanho('teste',5);
		$this->assertSame(null,$this->MitiValidacao->tamanho('',5));
	}
	
	public function testExcessoTamanho(){
		$mensagem='O valor deve conter até 5 caractéres';
		$this->setExpectedException('Exception',$mensagem);
		$this->MitiValidacao->tamanho('testes',5);
	}
	
	public function testEmail(){
		$this->MitiValidacao->email('conta@dominio.com');
		$this->assertSame(null,$this->MitiValidacao->email(''));
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
		$this->MitiValidacao->upload('arquivo',2048,array('jpeg','png','gif'));
		
		$teste=$this->MitiValidacao
			->upload('nao_existe',2048,array('jpeg','png','gif'));
		
		$this->assertSame(null,$teste);
	}
	
	public function testUploadExcessoPeso(){
		$mensagem='O arquivo excede o tamanho permitido';
		$this->setExpectedException('Exception',$mensagem);
		$this->MitiValidacao->upload('arquivo',1024,array('jpeg','png','gif'));
	}
	
	public function testUploadTipoInvalido(){
		$this->setExpectedException('Exception','O tipo do arquivo é inválido');
		$this->MitiValidacao->upload('arquivo',2048,array('doc','pdf','xls'));
	}
	
	public function testUploadImagem(){
		$this->MitiValidacao->uploadImagem('arquivo',16,16);
		
		$teste=$this->MitiValidacao->uploadImagem('nao_existe',16,16);
		$this->assertSame(null,$teste);
	}
	
	public function testUploadImagemExcessoLargura(){
		$mensagem='A largura da imagem é menor do que o mínimo permitido';
		$this->setExpectedException('Exception',$mensagem);
		$this->MitiValidacao->uploadImagem('arquivo',20,16);
	}
	
	public function testUploadImagemExcessoAltura(){
		$mensagem='A altura da imagem é menor do que o mínimo permitido';
		$this->setExpectedException('Exception',$mensagem);
		$this->MitiValidacao->uploadImagem('arquivo',16,20);
	}
	
	public function testUploadImagemProporcaoExcessoVertical(){
		$mensagem='A proporção da imagem é inválida, excedendo verticalmente';
		$this->setExpectedException('Exception',$mensagem);
		$this->MitiValidacao->uploadImagem('arquivo',16,8);
	}
	
	public function testUploadImagemProporcaoExcessoHorizontal(){
		$mensagem='A proporção da imagem é inválida, excedendo horizontalmente';
		$this->setExpectedException('Exception',$mensagem);
		$this->MitiValidacao->uploadImagem('arquivo',8,16);
	}
	
	public function testCpf(){
		$this->MitiValidacao->Cpf('27981094003');
		$this->assertSame(null,$this->MitiValidacao->CPF(''));
	}
	
	public function testCpfExcessoCaracteres(){
		$this->setExpectedException('Exception','#1 - O CPF é inválido');
		$this->MitiValidacao->Cpf('279810940033');
	}
	
	public function testCpfPresencaLetra(){
		$this->setExpectedException('Exception','#2 - O CPF é inválido');
		$this->MitiValidacao->Cpf('279810a4003');
	}
	
	public function testCpfSequenciaIgual(){
		$this->setExpectedException('Exception','#3 - O CPF é inválido');
		$this->MitiValidacao->Cpf('88888888888');
	}
	
	public function testCpfDigitoInvalido(){
		$this->setExpectedException('Exception','#4 - O CPF é inválido');
		$this->MitiValidacao->Cpf('27981094004');
	}
	
	public function testCnpj(){
		$this->MitiValidacao->Cnpj('87210343000169');
		$this->assertSame(null,$this->MitiValidacao->CNPJ(''));
	}
	
	public function testCnpjExcessoCaracteres(){
		$this->setExpectedException('Exception','#1 - O CNPJ é inválido');
		$this->MitiValidacao->Cnpj('872103430001699');
	}
	
	public function testCnpjPresencaLetra(){
		$this->setExpectedException('Exception','#2 - O CNPJ é inválido');
		$this->MitiValidacao->Cnpj('87210343a00169');
	}
	
	public function testCnpjSequenciaZeros(){
		$this->setExpectedException('Exception','#3 - O CNPJ é inválido');
		$this->MitiValidacao->Cnpj('00000000000000');
	}
	
	public function testCnpjDigitoInvalido(){
		$this->setExpectedException('Exception','#4 - O CNPJ é inválido');
		$this->MitiValidacao->Cnpj('87210343000159');
	}
	
	public function testCnpjDigitoInvalidoComZero(){
		$this->setExpectedException('Exception','#4 - O CNPJ é inválido');
		$this->MitiValidacao->Cnpj('80911582000106');
	}
}