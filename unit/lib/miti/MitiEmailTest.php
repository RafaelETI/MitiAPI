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
		$teste=$this->obterCabecalhoBasico()
			.$this->obterCabecalhoMensagem()
			.$this->obterCabecalhoAnexos()
			.'--485df3a43ab6dc02a02d96b66f8eb244--';
		
		$cabecalho=$this->MitiEmail
			->setUid('485df3a43ab6dc02a02d96b66f8eb244')
			->setCc('cc@dominio.com')
			->setBcc('bcc@dominio.com')
			->setReplyTo('replyto@dominio.com')
			->setAnexos('arquivo')
			->obterCabecalho('nome@dominio.com','It works!');
		
		$this->assertSame($teste,$cabecalho);
	}
	
	private function obterCabecalhoBasico(){
		$cabecalho='From: nome@dominio.com'."\r\n";
		$cabecalho.='Reply-To: replyto@dominio.com'."\r\n";
		$cabecalho.='Cc: cc@dominio.com'."\r\n";
		$cabecalho.='Bcc: bcc@dominio.com'."\r\n";
		$cabecalho.='MIME-Version: 1.0'."\r\n";
		$cabecalho.='Content-Type: multipart/mixed; boundary="485df3a43ab6dc02a02d96b66f8eb244"'."\r\n\r\n";
		$cabecalho.='This is a multi-part message in MIME format.'."\r\n";
		
		return $cabecalho;
	}
	
	private function obterCabecalhoMensagem(){
		$cabecalho='--485df3a43ab6dc02a02d96b66f8eb244'."\r\n";
		$cabecalho.='Content-type:text/html; charset=iso-8859-1'."\r\n";
		$cabecalho.='Content-Transfer-Encoding: 7bit'."\r\n\r\n";
		$cabecalho.='It works!'."\r\n\r\n";
		
		return $cabecalho;
	}
	
	private function obterCabecalhoAnexos(){
		$cabecalho='--485df3a43ab6dc02a02d96b66f8eb244'."\r\n";
		$cabecalho.='Content-Type: application/octet-stream; name="mitiunit.txt"'."\r\n";
		$cabecalho.='Content-Transfer-Encoding: base64'."\r\n";
		$cabecalho.='Content-Disposition: attachment; filename="mitiunit.txt"'."\r\n\r\n";
		//adicao de mais um '\r\n' por causa do final do arquivo
		$cabecalho.='TWl0aUVtYWlsOjpvYnRlckNhYmVjYWxobygp'."\r\n\r\n\r\n";
		
		return $cabecalho;
	}
}
