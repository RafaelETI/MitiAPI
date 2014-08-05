<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Envio de e-mail
 * 
 * � sempre enviado no formato HTML.
 * 
 * Permite defini��es de c�pias e anexos.
 */
class Email{
	/**
	 * @var string Delimita��es do cabe�alho. Deve ser um valor �nico.
	 */
	private $uid;
	
	/**
	 * @var string C�pia carbono.
	 */
	private $cc='';
	
	/**
	 * @var string C�pia carbono oculta.
	 */
	private $bcc='';
	
	/**
	 * @var string Responder para. Ainda descobrindo a utilidade.
	 */
	private $replyto='';
	
	/**
	 * @var string Name do formul�rio dos arquivos.
	 */
	private $anexos='';
	
	/**
	 * Define o UID
	 * 
	 * @api
	 */
	public function __construct(){
		$this->uid=md5(uniqid(time()));
	}
	
	/**
	 * Define o e-mail da c�pia carbono
	 * 
	 * @api
	 * @param string $cc
	 * @return Email
	 */
	public function setCc($cc){
		$this->cc=$cc;
		return $this;
	}
	
	/**
	 * Define o e-mail da c�pia carbono oculta
	 * 
	 * Evite. � falta de educa��o.
	 * 
	 * @api
	 * @param string $bcc
	 * @return Email
	 */
	public function setBcc($bcc){
		$this->bcc=$bcc;
		return $this;
	}
	
	/**
	 * Define o e-mail para resposta
	 * 
	 * @api
	 * @param string $replyto
	 * @return Email
	 */
	public function setReplyTo($replyto){
		$this->replyto=$replyto;
		return $this;
	}
	
	/**
	 * Define o name do formul�rio dos arquivos
	 * 
	 * O valor deve ser passado sem colchetes, mesmo em caso de upload m�ltiplo,
	 * ao passo que o name do formul�rio deve sempre possu�-los, mesmo se o
	 * upload n�o for m�ltiplo.
	 * 
	 * @api
	 * @param string $anexos
	 * @return Email
	 */
	public function setAnexos($anexos){
		$this->anexos=$anexos;
		return $this;
	}
	
	/**
	 * Envia o e-mail
	 * 
	 * O remetente n�o � passado como par�metro da fun��o mail() porque ele
	 * faz parte do cabe�alho.
	 * 
	 * Basta existir um arquivo, o qual foi configurado para o PHP, mesmo que
	 * ele n�o fa�a nada, que a mail() retorna true. Isso � interessante para o
	 * ambiente de desenvolvimento.
	 * 
	 * @api
	 * @param string $destinatario
	 * @param string $assunto
	 * @param string $mensagem
	 * @param string $remetente
	 * @param string $charset
	 * @throws \Exception
	 */
	public function enviar(
		$destinatario,$assunto,$mensagem,$remetente,$charset=CHARSET
	){
		if(
			!mail(
				$destinatario,
				$this->codificarAssunto($charset,$assunto),
				'',
				$this->montarCabecalho($remetente,$mensagem,$charset)
			)
		){
			throw new \Exception('Houve um erro ao enviar o e-mail.');
		}
	}
	
	/**
	 * Monta a unifica��o de todo o cabe�alho
	 * 
	 * @param string $remetente
	 * @param string $mensagem
	 * @param string $charset
	 * @return string
	 */
	private function montarCabecalho($remetente,$mensagem,$charset){
		return
			$this->montarCabecalhoBasico($remetente)
			.$this->montarCabecalhoMensagem($charset,$mensagem)
			.$this->montarCabecalhoAnexos()
		;
	}
	
	/**
	 * Monta a string do cabe�alho b�sico
	 * 
	 * @param string $remetente
	 * @return string
	 */
	private function montarCabecalhoBasico($remetente){
		return
			'From: '.$remetente."\r\n"
			.'Reply-To: '.$this->replyto."\r\n"
			.'Cc: '.$this->cc."\r\n"
			.'Bcc: '.$this->bcc."\r\n"
			.'MIME-Version: 1.0'."\r\n"
			.'Content-Type: multipart/mixed; boundary="'.$this->uid.'"'."\r\n\r\n"
			.'This is a multi-part message in MIME format.'."\r\n"
		;
	}
	
	/**
	 * Monta a string do cabe�alho da mensagem
	 * 
	 * @param string $charset
	 * @param string $mensagem
	 * @return string
	 */
	private function montarCabecalhoMensagem($charset,$mensagem){
		return
			'--'.$this->uid."\r\n"
			.'Content-type:text/html; charset='.$charset."\r\n"
			.'Content-Transfer-Encoding: 7bit'."\r\n\r\n"
			.$mensagem."\r\n\r\n"
		;
	}
	
	/**
	 * Monta a string do cabe�alho dos anexos
	 * 
	 * @return string
	 */
	private function montarCabecalhoAnexos(){
		$cabecalho='';
		
		if($this->anexos&&$_FILES[$this->anexos]['tmp_name'][0]){
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
	
	/**
	 * Codifica o assunto
	 * 
	 * Estranho, mas funciona.
	 * 
	 * @param string $charset
	 * @param string $assunto
	 * @return string
	 */
	private function codificarAssunto($charset,$assunto){
		return '=?'.$charset.'?b?'.base64_encode($assunto).'?=';
	}
}
