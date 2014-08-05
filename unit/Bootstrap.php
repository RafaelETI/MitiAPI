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
 * Essa classe � instanciada aqui mesmo.
 */
class Bootstrap{
	/**
	 * @var array 
	 */
	private $config=array();
	
	public function __construct(){
		$this
			->config()
			->ambiente()
			->erro()
			->timezone()
			->charset()
			->raiz()
			->banco()
			->sessao()
			->autoload()
		;
	}
	
	/**
	 * Define os par�metros de configura��o do sistema
	 * 
	 * Esse � o �nico m�todo que deve ser alterado para a parametriza��o do sistema.
	 * 
	 * Caso surjam mais configura��es de ambientes, adicion�-las onde for cab�vel.
	 * 
	 * @return Bootstrap
	 */
	private function config(){
		$this->config['ambiente']=0;
		$this->config['timezone']='America/Sao_Paulo';
		$this->config['charset']='iso-8859-1';

		$this->config['banco'][0]=array(
			'servidor'=>'localhost',
			'usuario'=>'root',
			'senha'=>'root',
			'banco'=>'miti_unit',
			'charset'=>'latin1',
		);
		
		return $this;
	}
	
	/**
	 * Configura o ambiente do sistema
	 * 
	 * H� a inten��o de que esse seja o �nico ponto de manuten��o ao trocar o
	 * sistema de ambiente.
	 * 
	 * @return Bootstrap
	 */
	private function ambiente(){
		define('AMBIENTE',$this->config['ambiente']);
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
		date_default_timezone_set($this->config['timezone']);
		return $this;
	}
	
	/**
	 * Define o charset do sistema
	 * 
	 * @return Config
	 */
	private function charset(){
		define('CHARSET',$this->config['charset']);
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
		define('BD_SERVIDOR',$this->config['banco'][AMBIENTE]['servidor']);
		define('BD_USUARIO',$this->config['banco'][AMBIENTE]['usuario']);
		define('BD_SENHA',$this->config['banco'][AMBIENTE]['senha']);
		define('BD_BANCO',$this->config['banco'][AMBIENTE]['banco']);
		define('BD_CHARSET',$this->config['banco'][AMBIENTE]['charset']);
		
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
	 * Para os nomes dos namespaces devem haver pastas de mesmo nome, come�ando
	 * da ra�z do sistema, tendo, por fim, um arquivo com o mesmo nome da classe.
	 * 
	 * Todos os nomes devem respeitar as mesmas caixas altas e baixas, tanto no
	 * c�digo, quanto no sistema de arquivos.
	 * 
	 * @return Bootstrap
	 */
	private function autoload(){
		spl_autoload_register(function($Classe){
			$arquivo=RAIZ."/$Classe.php";
			
			if(file_exists($arquivo)){
				require $arquivo;
			}
		});
		
		return $this;
	}
}
