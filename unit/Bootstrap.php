<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
new Bootstrap;

/**
 * Bootstrap para o PHPUnit
 * 
 * Esse arquivo � instanciado aqui mesmo, e seu diret�rio � considerado a ra�z dos
 * testes.
 */
class Bootstrap{
	public function __construct(){
		$this
			->ambiente()
			->banco()
			->erro()
			->timezone()
			->raiz()
			->sessao()
			->autoload()
		;
	}
	
	/**
	 * Configura o ambiente do sistema
	 * 
	 * Por padr�o essa classe tem defini��es para dois ambientes. Caso queira-se
	 * adicionar mais, deve-se adicionar novas configura��es nos outros m�todos
	 * cab�veis.
	 * 
	 * H� a inten��o de que esse seja o �nico ponto de manuten��o ao trocar o
	 * sistema de ambiente.
	 * 
	 * @return Bootstrap
	 */
	private function ambiente(){
		define('AMBIENTE',1);
		return $this;
	}
	
	/**
	 * Configura a conex�o com o banco de dados
	 * 
	 * O MySQL aceita, dentre outros, os charsets latin1 e utf8, escritos dessa
	 * forma.
	 * 
	 * Se o servidor do banco de dados for o mesmo de onde o sistema est�
	 * hospedado, usar localhost.
	 * 
	 * @return Bootstrap
	 */
	private function banco(){
		if(AMBIENTE===1){
			define('BD_SERVIDOR','localhost');
			define('BD_USUARIO','root');
			define('BD_SENHA','root');
			define('BD_BANCO','miti_unit');
			define('BD_CHARSET','latin1');
		}else if(AMBIENTE===2){
			define('BD_SERVIDOR','localhost');
			define('BD_USUARIO','root');
			define('BD_SENHA','root');
			define('BD_BANCO','miti_unit');
			define('BD_CHARSET','latin1');
		}
		
		return $this;
	}
	
	/**
	 * Configura como o PHP trata os erros do sistema
	 * 
	 * @return Bootstrap
	 */
	private function erro(){
		error_reporting(-1);
		ini_set('display_errors',1);
		return $this;
	}
	
	/**
	 * Configura a timezone do PHP
	 * 
	 * Vide a lista de timezones que o PHP suporta:
	 * {@link http://php.net/manual/en/timezones.php}.
	 * 
	 * @return Bootstrap
	 */
	private function timezone(){
		date_default_timezone_set('America/Sao_Paulo');
		return $this;
	}
	
	/**
	 * Configura a raiz do sistema
	 * 
	 * @return Bootstrap
	 */
	private function raiz(){
		define('RAIZ',__DIR__.'/..');
		return $this;
	}
	
	/**
	 * Inicia a sess�o
	 * 
	 * @return Bootstrap
	 */
	private function sessao(){
		session_start();
		return $this;
	}
	
	/**
	 * Configura a fun��o de autoload de classes
	 * 
	 * O nome completamente qualificado da classe deve conter apenas um n�vel de
	 * namespace mais o nome da classe. Para o nome do namespace deve haver uma
	 * pasta de mesmo nome, mas com letras min�sculas, na ra�z do sistema, com
	 * um arquivo com o mesmo nome da classe dentro dela.
	 * 
	 * Os nomes das classes, devem respeitar as mesmas caixas altas e baixas,
	 * tanto no c�digo, quanto no arquivo. Enquanto para o namespace, o nome da
	 * pasta deve ser todo min�sculo, podendo o nome, no c�digo, ser de qualquer
	 * forma.
	 * 
	 * Exemplo de namespace: namespace Pasta; -> pasta/.
	 * Exemplo de classe: class Abstracao{; -> Abstracao.php.
	 * 
	 * @return Bootstrap
	 * 
	 * @todo O ideal � que o nome completamente qualificado da classe n�o esteja
	 * restrito � apenas um n�vel de namespace.
	 */
	private function autoload(){
		spl_autoload_register(function($fully){
			$partes=explode('\\',$fully);
			$namespace=strtolower(reset($partes));
			$Classe=end($partes);

			require RAIZ."/$namespace/$Classe.php";
		});
		
		return $this;
	}
}
