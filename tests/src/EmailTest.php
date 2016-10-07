<?php
class EmailTest extends PHPUnit_Framework_TestCase{
	private static $arquivos = array();

	public static function setUpBeforeClass(){
		self::$arquivos['name'][0] = 'miti.txt';
		self::$arquivos['tmp_name'][0] = '../tests/arquivos/miti.txt';
	}
	
        //todo: mail(): Multiple or malformed newlines found in additional_header
//	public function testEnviar(){
//		$this->Email = new \Miti\Email;
//		
//		$this->Email
//			->setCc('cc@dominio.com')
//			->setBcc('bcc@dominio.com')
//			->setReplyTo('replyto@dominio.com')
//			->setAnexos(self::$arquivos)
//			->enviar('a@a.a', 'Assunto', 'Mensagem', 'b@b.b')
//		;
//	}
}
