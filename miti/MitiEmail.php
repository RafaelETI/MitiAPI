<?php
/**
 * MitiAPI, 2014.
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 */
class MitiEmail{
	private $uid;
	private $cc='';
	private $bcc='';
	private $replyto='';
	private $anexos=false;
	
	public function __construct(){
		$this->uid=md5(uniqid(time()));
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
		if(
			!mail(
				$dest,
				$this->obterAssuntoCodificado($charset,$assunto),
				'',
				$this->obterCabecalho($remet,$msg,$charset)
			)
		){
			throw new Exception('Houve um erro ao enviar o e-mail');
		}
	}
	
	private function obterCabecalho($remet,$msg,$charset='iso-8859-1'){
		return
			$this->obterCabecalhoBasico($remet)
			.$this->obterCabecalhoMensagem($charset,$msg)
			.$this->obterCabecalhoAnexos()
		;
	}
	
	private function obterCabecalhoBasico($remet){
		return
			'From: '.$remet."\r\n"
			.'Reply-To: '.$this->replyto."\r\n"
			.'Cc: '.$this->cc."\r\n"
			.'Bcc: '.$this->bcc."\r\n"
			.'MIME-Version: 1.0'."\r\n"
			.'Content-Type: multipart/mixed; boundary="'.$this->uid.'"'."\r\n\r\n"
			.'This is a multi-part message in MIME format.'."\r\n"
		;
	}
	
	private function obterCabecalhoMensagem($charset,$msg){
		return
			'--'.$this->uid."\r\n"
			.'Content-type:text/html; charset='.$charset."\r\n"
			.'Content-Transfer-Encoding: 7bit'."\r\n\r\n"
			.$msg."\r\n\r\n"
		;
	}
	
	private function obterCabecalhoAnexos(){
		$cabecalho='';
		
		if($this->anexos&&$_FILES[$this->anexos]['tmp_name'][0]){
			//sempre colocar o valor do name do file com [] no formulario
			foreach($_FILES[$this->anexos]['tmp_name'] as $i=>$v){
				$nome=basename($_FILES[$this->anexos]['name'][$i]);
				$conteudo=chunk_split(base64_encode(file_get_contents($v)));
				
				$cabecalho=
					'--'.$this->uid."\r\n"
					.'Content-Type: application/octet-stream; name="'.$nome.'"'."\r\n"
					.'Content-Transfer-Encoding: base64'."\r\n"
					.'Content-Disposition: attachment; filename="'.$nome.'"'."\r\n\r\n"
					.$conteudo."\r\n\r\n"
				;
			}
		}
		
		$cabecalho.='--'.$this->uid.'--';
		return $cabecalho;
	}
	
	private function obterAssuntoCodificado($charset,$assunto){
		return '=?'.$charset.'?b?'.base64_encode($assunto).'?=';
	}
}
