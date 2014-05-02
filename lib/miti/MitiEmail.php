<?php
class MitiEmail{
	private $uid;
	private $cc='';
	private $bcc='';
	private $replyto='';
	private $anexos=false;
	
	public function __construct(){
		$this->uid=md5(uniqid(time()));
	}
	
	public function setUid($uid){
		$this->uid=$uid;
		return $this;
	}
	
	public function setCc($cc){
		$this->cc=$cc;
		return $this;
	}
	
	public function setBcc($bcc){
		$this->bcc=$bcc;
		return $this;
	}
	
	public function setReplyTo($replyto){
		$this->replyto=$replyto;
		return $this;
	}
	
	public function setAnexos($anexos){
		$this->anexos=$anexos;
		return $this;
	}
	
	public function enviar($dest,$assunto,$msg,$remet,$charset='iso-8859-1'){
		$cabecalho=$this->obterCabecalho($remet,$msg,$charset);
		$assunto=$this->obterAssuntoCodificado($charset,$assunto);
		
		if(!mail($dest,$assunto,'',$cabecalho)){
			throw new Exception('Houve um erro ao enviar o e-mail');
		}
	}
	
	public function obterCabecalho($remet,$msg,$charset='iso-8859-1'){
		$cabecalho=$this->obterCabecalhoBasico($remet);
		$cabecalho.=$this->obterCabecalhoMensagem($charset,$msg);
		$cabecalho.=$this->obterCabecalhoAnexos();
		
		return $cabecalho;
	}
	
	private function obterCabecalhoBasico($remet){
		$cabecalho='From: '.$remet."\r\n";
		$cabecalho.='Reply-To: '.$this->replyto."\r\n";
		$cabecalho.='Cc: '.$this->cc."\r\n";
		$cabecalho.='Bcc: '.$this->bcc."\r\n";
		$cabecalho.='MIME-Version: 1.0'."\r\n";
		$cabecalho.='Content-Type: multipart/mixed; boundary="'.$this->uid.'"'."\r\n\r\n";
		$cabecalho.='This is a multi-part message in MIME format.'."\r\n";
		
		return $cabecalho;
	}
	
	private function obterCabecalhoMensagem($charset,$msg){
		$cabecalho='--'.$this->uid."\r\n";
		$cabecalho.='Content-type:text/html; charset='.$charset."\r\n";
		$cabecalho.='Content-Transfer-Encoding: 7bit'."\r\n\r\n";
		$cabecalho.=$msg."\r\n\r\n";
		return $cabecalho;
	}
	
	private function obterCabecalhoAnexos(){
		$cabecalho='';
		
		if($this->anexos&&$_FILES[$this->anexos]['tmp_name'][0]){
			//sempre colocar o valor do name do "file" com "[]" no formulario
			foreach($_FILES[$this->anexos]['tmp_name'] as $i=>$v){
				$nome=basename($_FILES[$this->anexos]['name'][$i]);
				
				$conteudo=chunk_split(base64_encode(file_get_contents($v)));
				
				$cabecalho='--'.$this->uid."\r\n";
				$cabecalho.='Content-Type: application/octet-stream; name="'.$nome.'"'."\r\n";
				$cabecalho.='Content-Transfer-Encoding: base64'."\r\n";
				$cabecalho.='Content-Disposition: attachment; filename="'.$nome.'"'."\r\n\r\n";
				$cabecalho.=$conteudo."\r\n\r\n";
			}
		}
		
		$cabecalho.='--'.$this->uid.'--';
		return $cabecalho;
	}
	
	private function obterAssuntoCodificado($charset,$assunto){
		return '=?'.$charset.'?b?'.base64_encode($assunto).'?=';
	}
}
