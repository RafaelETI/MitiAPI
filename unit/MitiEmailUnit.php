<?php
class MitiEmailUnit extends MitiUnit{
	private $MitiEmail;
	
	public function __construct(){
		$this->MitiEmail=new MitiEmail();
		$this->enviar();
	}
	
	private function enviar(){
		$this->declararFiles();
		$cabecalho=$this->obterCabecalho();
		
		$this->MitiEmail->setUid('485df3a43ab6dc02a02d96b66f8eb244');
		$this->MitiEmail->setAnexos('arquivo');
		$this->afirmar($this->MitiEmail->obterCabecalho('nome@dominio.com','It works!'),$cabecalho,__METHOD__);
	}
	
	private function declararFiles(){
		$_FILES['arquivo']['name'][0]='mitiunit.txt';
		$_FILES['arquivo']['tmp_name'][0]=RAIZ.'msc/mitiunit.txt';
	}
	
	private function obterCabecalho(){
		$cabecalho='';
		$cabecalho.=$this->obterCabecalhoBasico();
		$cabecalho.=$this->obterCabecalhoMensagem();
		$cabecalho.=$this->obterCabecalhoAnexos();
		$cabecalho.='--485df3a43ab6dc02a02d96b66f8eb244--';
		
		return $cabecalho;
	}
	
	private function obterCabecalhoBasico(){
		$cabecalho='From: nome@dominio.com'."\r\n";
		$cabecalho.='Reply-To: '."\r\n";
		$cabecalho.='Cc: '."\r\n";
		$cabecalho.='Bcc: '."\r\n";
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
		$cabecalho.='TWl0aUVtYWlsOjpvYnRlckNhYmVjYWxobygpCg=='."\r\n\r\n\r\n";
		
		return $cabecalho;
	}
}
?>
