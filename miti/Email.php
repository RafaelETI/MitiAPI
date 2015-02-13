<?php
/**
 * Miti API, 2014 - 2015
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Envio de e-mail
 * 
 * É sempre enviado no formato HTML.
 * 
 * Permite definições de cópias e anexos.
 */
class Email{
	/**
	 * @var string Delimitações do cabeçalho. Deve ser um valor único.
	 */
	private $uid;
	
	/**
	 * @var string Cópia carbono.
	 */
	private $cc = '';
	
	/**
	 * @var string Cópia carbono oculta.
	 */
	private $bcc = '';
	
	/**
	 * @var string Responder para. Ainda descobrindo a utilidade.
	 */
	private $replyTo = '';
	
	/**
	 * @var array[]
	 */
	private $anexos;
	
	/**
	 * Define o UID
	 * 
	 * @api
	 */
	public function __construct(){
		$this->uid = md5(uniqid(time()));
	}
	
	/**
	 * Define o e-mail da cópia carbono
	 * 
	 * @api
	 * @param string $cc
	 * @return Email
	 */
	public function setCc($cc){
		$this->cc = $cc;
		return $this;
	}
	
	/**
	 * Define o e-mail da cópia carbono oculta
	 * 
	 * Evite. É falta de educação.
	 * 
	 * @api
	 * @param string $bcc
	 * @return Email
	 */
	public function setBcc($bcc){
		$this->bcc = $bcc;
		return $this;
	}
	
	/**
	 * Define o e-mail para resposta
	 * 
	 * @api
	 * @param string $replyTo
	 * @return Email
	 */
	public function setReplyTo($replyTo){
		$this->replyTo = $replyTo;
		return $this;
	}
	
	/**
	 * Define o vetor de informações de arquivos
	 * 
	 * @api
	 * @param array[] $anexos No mesmo formato de $_FILES['...'] múltiplo.
	 * @return Email
	 */
	public function setAnexos($anexos){
		$this->anexos = $anexos;
		return $this;
	}
	
	/**
	 * Envia o e-mail
	 * 
	 * O remetente não é passado como parâmetro da função mail() porque ele
	 * faz parte do cabeçalho.
	 * 
	 * Basta existir um arquivo, o qual foi configurado para o PHP, mesmo que
	 * ele não faça nada, que a mail() retorna true. Isso é interessante para o
	 * ambiente de desenvolvimento.
	 * 
	 * @api
	 * @param string $destinatario
	 * @param string $assunto
	 * @param string $mensagem
	 * @param string $remetente
	 * @param string $charset
	 * @throws \RuntimeException
	 */
	public function enviar($destinatario, $assunto, $mensagem, $remetente, $charset = CFG_CHARSET){
		if(!mail($destinatario, $this->codificarAssunto($charset, $assunto), '', $this->montarCabecalho($remetente, $mensagem, $charset))){
			throw new \RuntimeException('Houve um erro ao enviar o e-mail.');
		}
	}
	
	/**
	 * Monta a unificação de todo o cabeçalho
	 * 
	 * @param string $remetente
	 * @param string $mensagem
	 * @param string $charset
	 * @return string
	 */
	private function montarCabecalho($remetente, $mensagem, $charset){
		return
			$this->montarCabecalhoBasico($remetente)
			.$this->montarCabecalhoMensagem($charset, $mensagem)
			.$this->montarCabecalhoAnexos()
		;
	}
	
	/**
	 * Monta a string do cabeçalho básico
	 * 
	 * @param string $remetente
	 * @return string
	 */
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
	
	/**
	 * Monta a string do cabeçalho da mensagem
	 * 
	 * @param string $charset
	 * @param string $mensagem
	 * @return string
	 */
	private function montarCabecalhoMensagem($charset, $mensagem){
		return
			"--$this->uid\r\n"
			."Content-type:text/html; charset=$charset\r\n"
			."Content-Transfer-Encoding: 7bit\r\n\r\n"
			."$mensagem\r\n\r\n"
		;
	}
	
	/**
	 * Monta a string do cabeçalho dos anexos
	 * 
	 * @return string
	 */
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
	
	/**
	 * Codifica o assunto
	 * 
	 * Estranho, mas funciona.
	 * 
	 * @param string $charset
	 * @param string $assunto
	 * @return string
	 */
	private function codificarAssunto($charset, $assunto){
		return "=?$charset?b?".base64_encode($assunto).'?=';
	}
}
