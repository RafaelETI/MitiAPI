<?php
/**
 * Miti API, 2014 - 2015
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Controle de mensagens de status de procedimentos
 * 
 * Todas as mensagens são imprimidas atráves de alertas de JavaScript. A classe
 * e o método devem ser instanciados em um arquivo unificado (i.e. que todas as
 * páginas chamem).
 */
class Status{
	/**
	 * Retorna HTML mais JavaScript que gera a mensagem do status
	 * 
	 * Retornando-se nulo quando a sessão não existir, permite que o método
	 * seja chamado sempre sem que haja problemas.
	 * 
	 * Por conveniência de implementação, converte-se o boleano true para uma
	 * string genérica, assim os métodos precisam armazenar somente o boleano
	 * na sessão.
	 * 
	 * Remove-se a sessão para que a mensagem não seja mostrada novamente em
	 * caso de recarregamento da página.
	 * 
	 * @global string[] $_SESSION Dependência do escopo global.
	 * 
	 * @return string
	 */
	public static function alertar(){
		if(!isset($_SESSION['status'])){return;}
		if($_SESSION['status'] === true){$_SESSION['status'] = 'Concluído com sucesso.';}
		
		$_SESSION['status'] = addslashes($_SESSION['status']);
		
		$alerta = "<script>alert('{$_SESSION['status']}');</script>";
		unset($_SESSION['status']);
		
		return $alerta;
	}
}
