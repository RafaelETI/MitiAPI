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
 * Esse arquivo é instanciado aqui mesmo, e seu diretório é considerado a raíz dos
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
	 * Por padrão essa classe tem definições para dois ambientes. Caso queira-se
	 * adicionar mais, deve-se adicionar novas configurações nos outros métodos
	 * cabíveis.
	 * 
	 * Há a intenção de que esse seja o único ponto de manutenção ao trocar o
	 * sistema de ambiente.
	 * 
	 * @return \Bootstrap
	 */
	private function ambiente(){
		define('AMBIENTE',1);
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
	 * @return \Bootstrap
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
	 * @return \Bootstrap
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
	 * @return \Bootstrap
	 */
	private function timezone(){
		date_default_timezone_set('America/Sao_Paulo');
		return $this;
	}
	
	/**
	 * Configura a raiz do sistema
	 * 
	 * @return \Bootstrap
	 */
	private function raiz(){
		define('RAIZ',__DIR__.'/../');
		return $this;
	}
	
	/**
	 * Inicia a sessão
	 * 
	 * @return \Bootstrap
	 */
	private function sessao(){
		session_start();
		return $this;
	}
	
	/**
	 * Configura a função de autoload de classes
	 * 
	 * Ela não atende nem ao PSR-0 ({@link http://www.php-fig.org/psr/psr-0/}),
	 * nem ao PSR-4 ({@link http://www.php-fig.org/psr/psr-4/}).
	 * 
	 * Adicionar os diretórios desejados no array respectivo, e nomear o arquivo
	 * com o mesmo nome da classe, ex: Classe.php -> Classe{.
	 * 
	 * @return \Bootstrap
	 */
	private function autoload(){
		function mitiAutoload($Classe){
			foreach(array('adt','miti') as $v){
				if(file_exists(RAIZ.$v.'/'.$Classe.'.php')){
					require RAIZ.$v.'/'.$Classe.'.php';
					break;
				}
			}
		}
		
		spl_autoload_register('mitiAutoload');
		
		return $this;
	}
}
