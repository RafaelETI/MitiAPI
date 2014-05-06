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
		$this->MitiEmail->enviar('a@a.a','Assunto','Mensagem','b@b.b');
	}
	
	public function testObterCabecalho(){
		$this->assertSame(
			$this->obterCabecalhoBasico()
			.$this->obterCabecalhoMensagem()
			.$this->obterCabecalhoAnexos()
			.'--485df3a43ab6dc02a02d96b66f8eb244--',
			
			$this->MitiEmail
				->setUid('485df3a43ab6dc02a02d96b66f8eb244')
				->setCc('cc@dominio.com')
				->setBcc('bcc@dominio.com')
				->setReplyTo('replyto@dominio.com')
				->setAnexos('arquivo')
				->obterCabecalho('nome@dominio.com','It works!')
		);
	}
	
	private function obterCabecalhoBasico(){
		return
			'From: nome@dominio.com'."\r\n"
			.'Reply-To: replyto@dominio.com'."\r\n"
			.'Cc: cc@dominio.com'."\r\n"
			.'Bcc: bcc@dominio.com'."\r\n"
			.'MIME-Version: 1.0'."\r\n"
			.'Content-Type: multipart/mixed; boundary="485df3a43ab6dc02a02d96b66f8eb244"'."\r\n\r\n"
			.'This is a multi-part message in MIME format.'."\r\n"
		;
	}
	
	private function obterCabecalhoMensagem(){
		return
			'--485df3a43ab6dc02a02d96b66f8eb244'."\r\n"
			.'Content-type:text/html; charset=iso-8859-1'."\r\n"
			.'Content-Transfer-Encoding: 7bit'."\r\n\r\n"
			.'It works!'."\r\n\r\n"
		;
	}
	
	private function obterCabecalhoAnexos(){
		return
			'--485df3a43ab6dc02a02d96b66f8eb244'."\r\n"
			.'Content-Type: application/octet-stream; name="mitiunit.txt"'."\r\n"
			.'Content-Transfer-Encoding: base64'."\r\n"
			.'Content-Disposition: attachment; filename="mitiunit.txt"'."\r\n\r\n"
			//adicao de mais um "\r\n" por causa do final do eof
			.'TWl0aUVtYWlsOjpvYnRlckNhYmVjYWxobygp'."\r\n\r\n\r\n"
		;
	}
}
