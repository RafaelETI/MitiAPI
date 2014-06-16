<?php
class MitiEmailTest extends PHPUnit_Framework_TestCase{
	private $MitiEmail;
	
	protected function setUp(){
		$this->MitiEmail=new MitiEmail;
		$this->declararFiles();
	}
	
	private function declararFiles(){
		$_FILES['arquivo']['name'][0]='miti.txt';
		$_FILES['arquivo']['tmp_name'][0]=RAIZ.'unit/arquivo/miti.txt';
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
