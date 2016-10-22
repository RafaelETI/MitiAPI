<?php
namespace Miti;

class Email{
	private $uid;
	private $cc = '';
	private $bcc = '';
	private $replyTo = '';
	private $anexos;
	
	public function __construct(){
		$this->uid = md5(uniqid(time()));
	}
	
	public function setCc($cc){
		$this->cc = $cc;
		return $this;
	}
	
	public function setBcc($bcc){
		$this->bcc = $bcc;
		return $this;
	}
	
	public function setReplyTo($replyTo){
		$this->replyTo = $replyTo;
		return $this;
	}
	
	public function setAnexos($anexos){
		$this->anexos = $anexos;
		return $this;
	}
	
	public function enviar($destinatario, $assunto, $mensagem, $remetente, $charset = 'UTF-8'){
		if(!mail($destinatario, $this->codificarAssunto($charset, $assunto), '', $this->montarCabecalho($remetente, $mensagem, $charset))){
			throw new \RuntimeException('Houve um erro ao enviar o e-mail.');
		}
	}
	
	private function montarCabecalho($remetente, $mensagem, $charset){
		return
			$this->montarCabecalhoBasico($remetente)
			.$this->montarCabecalhoMensagem($charset, $mensagem)
			.$this->montarCabecalhoAnexos()
		;
	}
	
	private function montarCabecalhoBasico($remetente){
		return
			"From: $remetente\r\n"
			."Reply-To: $this->replyTo\r\n"
			."Cc: $this->cc\r\n"
			."Bcc: $this->bcc\r\n"
			."MIME-Version: 1.0\r\n"
			."Content-Type: multipart/mixed; boundary='$this->uid'\r\n\r\n"
			."This is a multi-part message in MIME format\r\n"
		;
	}
	
	private function montarCabecalhoMensagem($charset, $mensagem){
		return
			"--$this->uid\r\n"
			."Content-type:text/html; charset=$charset\r\n"
			."Content-Transfer-Encoding: 7bit\r\n\r\n"
			."$mensagem\r\n\r\n"
		;
	}
	
	private function montarCabecalhoAnexos(){
		$cabecalho = '';
		
		if($this->anexos){
			foreach($this->anexos['tmp_name'] as $i => $tmp){
				$nome = basename($this->anexos['name'][$i]);
				$conteudo = chunk_split(base64_encode(file_get_contents($tmp)));
				
				$cabecalho =
					"--$this->uid\r\n"
					."Content-Type: application/octet-stream; name='$nome'\r\n"
					."Content-Transfer-Encoding: base64\r\n"
					."Content-Disposition: attachment; filename='$nome'\r\n\r\n"
					."$conteudo\r\n\r\n"
				;
			}
		}
		
		return $cabecalho . "--$this->uid--";
	}
	
	private function codificarAssunto($charset, $assunto){
		return "=?$charset?b?".base64_encode($assunto).'?=';
	}
}
