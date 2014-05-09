<?php
class MitiEmailTest extends PHPUnit_Framework_TestCase{
	protected $MitiEmail;
	
	protected function setUp(){
		$this->MitiEmail=new MitiEmail;
		$this->declararFiles();
	}
	
	private function declararFiles(){
		$_FILES['arquivo']['name'][0]='mitiunit.txt';
		$_FILES['arquivo']['tmp_name'][0]=RAIZ.'msc/mitiunit.txt';
	}
	
	public function testEnviar(){
		$this->MitiEmail
			->setCc('cc@dominio.com')
			->setBcc('bcc@dominio.com')
			->setReplyTo('replyto@dominio.com')
			->setAnexos('arquivo')
			->enviar('a@a.a','Assunto','Mensagem','b@b.b')
		;
	}
}
