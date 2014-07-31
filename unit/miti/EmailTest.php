<?php
class EmailTest extends PHPUnit_Framework_TestCase{
	public static function setUpBeforeClass(){
		$_FILES['arquivo']['name'][0]='miti.txt';
		$_FILES['arquivo']['tmp_name'][0]=RAIZ.'/unit/arquivo/miti.txt';
	}
	
	public function testEnviar(){
		$this->Email=new \miti\Email;
		
		$this->Email
			->setCc('cc@dominio.com')
			->setBcc('bcc@dominio.com')
			->setReplyTo('replyto@dominio.com')
			->setAnexos('arquivo')
			->enviar('a@a.a','Assunto','Mensagem','b@b.b')
		;
	}
}
