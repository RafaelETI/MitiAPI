<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace Miti;

/**
 * Controle de mensagens de status de procedimentos
 * 
 * Todas as mensagens s�o imprimidas atr�ves de alertas de JavaScript. A classe
 * e o m�todo devem ser instanciados em um arquivo unificado (i.e. que todas as
 * p�ginas chamem).
 */
class Status{
	/**
	 * Retorna HTML mais JavaScript que gera a mensagem do status
	 * 
	 * Retornando-se nulo quando a sess�o n�o existir, permite que o m�todo
	 * seja chamado sempre sem que haja problemas.
	 * 
	 * Por conveni�ncia de implementa��o, converte-se o boleano true para uma
	 * string gen�rica, assim os m�todos precisam armazenar somente o boleano
	 * na sess�o.
	 * 
	 * Remove-se a sess�o para que a mensagem n�o seja mostrada novamente em
	 * caso de recarregamento da p�gina.
	 * 
	 * @api
	 * @global string[] $_SESSION Depend�ncia do escopo global.
	 * @return string
	 */
	public static function alertar(){
		if(!isset($_SESSION['status'])){
			return;
		}
		
		if($_SESSION['status']===true){
			$_SESSION['status']='Conclu�do com sucesso.';
		}
		
		$_SESSION['status']=addslashes($_SESSION['status']);
		
		$alerta='<script>alert("'.$_SESSION['status'].'");</script>';
		unset($_SESSION['status']);
		
		return $alerta;
	}
}
