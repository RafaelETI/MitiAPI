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
 * Essa classe é instanciada aqui mesmo.
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
			->salt()
			->raiz()
			->banco()
			->sessao()
			->autoload()
		;
	}
	
	/**
	 * Define os parâmetros de configuração do sistema
	 * 
	 * Esse é o único método que deve ser alterado para a parametrização do sistema.
	 * 
	 * Caso surjam mais configurações de ambientes, adicioná-las onde for cabível.
	 * 
	 * @return Bootstrap
	 */
	private function config(){
		$this->config['ambiente']=1;
		$this->config['timezone']='America/Sao_Paulo';
		$this->config['charset']='ISO-8859-1';
		$this->config['salt'] = '$1$mitiapi$$';

		$this->config['banco']['charset'] = 'latin1';
		$this->config['banco'][1]['servidor'] = 'localhost';
		$this->config['banco'][1]['usuario'] = 'root';
		$this->config['banco'][1]['senha'] = 'root';
		$this->config['banco'][1]['nome'] = 'miti_api';
		
		return $this;
	}
	
	/**
	 * Configura o ambiente do sistema
	 * 
	 * Há a intenção de que esse seja o único ponto de manutenção ao trocar o
	 * sistema de ambiente.
	 * 
	 * @return Bootstrap
	 */
	private function ambiente(){
		define('CFG_AMBIENTE', $this->config['ambiente']);
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
		ini_set('display_startup_errors',1);
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
		define('CFG_CHARSET', $this->config['charset']);
		return $this;
	}
	
	/**
	 * Configura o salt
	 * 
	 * Recomenda-se usar a função crypt() para passar senhas por algoritmos de
	 * hash ({@link http://php.net/manual/en/function.crypt.php}).
	 * 
	 * @return Config
	 */
	private function salt(){
		define('CFG_SALT', $this->config['salt']);
		return $this;
	}
	
	/**
	 * Configura a raiz do sistema
	 * 
	 * @return Bootstrap
	 */
	private function raiz(){
		define('CFG_RAIZ', __DIR__.'/..');
		return $this;
	}
	
	/**
	 * Configura a conexão com o banco de dados
	 * 
	 * O MySQL aceita, dentre outros, os charsets latin1 e utf8, escritos dessa
	 * forma.
	 * 
	 * Se o servidor do banco de dados for o mesmo de onde o sistema está
	 * hospedado, usar localhost.
	 * 
	 * @return Bootstrap
	 */
	private function banco(){
		define('CFG_BANCO_CHARSET', $this->config['banco']['charset']);
		define('CFG_BANCO_SERVIDOR', $this->config['banco'][CFG_AMBIENTE]['servidor']);
		define('CFG_BANCO_USUARIO', $this->config['banco'][CFG_AMBIENTE]['usuario']);
		define('CFG_BANCO_SENHA', $this->config['banco'][CFG_AMBIENTE]['senha']);
		define('CFG_BANCO_NOME', $this->config['banco'][CFG_AMBIENTE]['nome']);
		
		return $this;
	}
	
	/**
	 * Inicia a sessão
	 * 
	 * @return Bootstrap
	 */
	private function sessao(){
		session_start();
		return $this;
	}
	
	/**
	 * Configura a função de autoload de classes
	 * 
	 * Para os nomes dos namespaces devem haver pastas de mesmo nome, começando
	 * da raíz do sistema, tendo, por fim, um arquivo com o mesmo nome da classe.
	 * 
	 * Todos os nomes devem respeitar as mesmas caixas altas e baixas, tanto no
	 * código, quanto no sistema de arquivos.
	 * 
	 * @return Bootstrap
	 */
	private function autoload(){
		spl_autoload_register(function($Classe){
			$arquivo = CFG_RAIZ."/$Classe.php";
			if(file_exists($arquivo)){require $arquivo;}
		});
		
		return $this;
	}
}
