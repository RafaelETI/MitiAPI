<?php
class MitiEmail{
	private $cc='';
	private $bcc='';
	private $replyto='';
	private $anexos=null;
	
	public function setCc($cc){
		$this->cc=$cc;
	}
	
	public function setBcc($bcc){
		$this->bcc=$bcc;
	}
	
	public function setReplyto($replyto){
		$this->replyto=$replyto;
	}
	
	public function setAnexos($anexos){
		$this->anexos=$anexos;
	}
	
	public function enviar($dest,$assunto,$msg,$remet,$charset='iso-8859-1'){
		$uid=md5(uniqid(time()));
		
		//basico
		$cabecalho='From: '.$remet."\r\n";
		$cabecalho.='Reply-To: '.$this->replyto."\r\n";
		$cabecalho.='Cc: '.$this->cc."\r\n";
		$cabecalho.='Bcc: '.$this->bcc."\r\n";
	
		$cabecalho.='MIME-Version: 1.0'."\r\n";
		$cabecalho.='Content-Type: multipart/mixed; boundary="'.$uid.'"'."\r\n\r\n";
		$cabecalho.='This is a multi-part message in MIME format.'."\r\n";
		
		//mensagem
		$cabecalho.='--'.$uid."\r\n";
		$cabecalho.='Content-type:text/html; charset='.$charset."\r\n";
		$cabecalho.='Content-Transfer-Encoding: 7bit'."\r\n\r\n";
		//recomendacao do manual
		$msg=wordwrap($msg,70,"\r\n");
		$cabecalho.=$msg."\r\n\r\n";
		
		//anexos
		if($this->anexos!=null&&$_FILES[$this->anexos]['tmp_name'][0]!=''){
			//sempre colocar o valor do name do "file" com "[]" no formulario
			foreach($_FILES[$this->anexos]['tmp_name'] as $i=>$v){
				$nome=basename($_FILES[$this->anexos]['name'][$i]);
			
				$conteudo=file_get_contents($v);
				$conteudo=chunk_split(base64_encode($conteudo));

				$cabecalho.='--'.$uid."\r\n";
				$cabecalho.='Content-Type: application/octet-stream; name="'.$nome.'"'."\r\n";
				$cabecalho.='Content-Transfer-Encoding: base64'."\r\n";
				$cabecalho.='Content-Disposition: attachment; filename="'.$nome.'"'."\r\n\r\n";
				$cabecalho.=$conteudo."\r\n\r\n";
			}
		}
		
		$cabecalho.='--'.$uid.'--';
		
		//usando a codificao tambem para o assunto
		$assunto='=?'.$charset.'?b?'.base64_encode($assunto).'?=';
		
		//a mensagem ja esta indo no cabecalho, deixar vazio no parametro da funcao
		if(mail($dest,$assunto,'',$cabecalho)==false){throw new Exception('Houve um erro ao enviar o e-mail');}
	}
}
?>
