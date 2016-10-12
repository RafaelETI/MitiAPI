<?php
/**
 * Miti API, 2014 - 2016
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Configuração do sistema
 */
class Config{
	/**
	 * @var mixed[]
	 */
	private $config = [];
	
	/**
	 * Chama todos os métodos da classe
	 */
	public function __construct(){
		session_start();
		$this->config()->erro()->sistema()->timezone()->charset()->raiz()->banco()->idioma()->autoload();
	}
	
	/**
	 * Define os parâmetros de configuração do sistema
	 * 
	 * Esse é o único método que deve ser alterado para a parametrização do sistema.
	 * 
	 * Caso surjam mais configurações de ambientes, adicioná-las onde for cabível.
	 * 
	 * @return Config
	 */
	private function config(){
		$this->config['ambiente'] = 1;
		$this->config['sistema'] = 'Miti API';
		$this->config['versao'] = '1.37';
		$this->config['timezone'] = 'America/Sao_Paulo';
		$this->config['charset'] = 'UTF-8';
		$this->config['salt'] = '$1$mitiapim$';
		
		$this->config['banco']['charset'] = 'utf8';
		$this->config['banco'][0]['servidor'] = '';
		$this->config['banco'][0]['usuario'] = '';
		$this->config['banco'][0]['senha'] = '';
		$this->config['banco'][0]['nome'] = '';
		$this->config['banco'][1]['servidor'] = 'localhost';
		$this->config['banco'][1]['usuario'] = 'root';
		$this->config['banco'][1]['senha'] = 'root';
		$this->config['banco'][1]['nome'] = 'miti_api';
		
		$this->config['rest']['servidor'] = 'http://service.example.com/rest.php';
		$this->config['rest']['usuario'] = 'usuario';
		$this->config['rest']['senha'] = 'senha';
		
		return $this;
	}
	
	public function getConfig(){return $this->config;}
	
	/**
	 * Configura como o PHP trata os erros do sistema
	 * 
	 * É uma das principais configurações de segurança. Nunca mostre os erros
	 * emitidos pelo PHP, diretamente na tela, no ambiente de produção!
	 * 
	 * @return Config
	 */
	private function erro(){
		error_reporting(-1);
		ini_set('display_errors', $this->config['ambiente']);
		return $this;
	}
	
	/**
	 * Configura o nome e a versão do sistema
	 * 
	 * Recomenda-se chamar esse parâmetro em algum lugar visível de todas as
	 * interfaces do sistema para que possa-se identificar facilmente se o sistema está
	 * configurado para o ambiente de produção ou não: se estiver mostrando a versão,
	 * está configurado para ambiente de desenvolvimento.
	 * 
	 * @return Config
	 */
	private function sistema(){
		if($this->config['ambiente']){$this->config['sistema'] .= ' '.$this->config['versao'];}
		return $this;
	}
	
	/**
	 * Configura a timezone do PHP
	 * 
	 * Vide lista de timezones que o PHP suporta:
	 * {@link http://php.net/manual/en/timezones.php}.
	 * 
	 * @return Config
	 */
	private function timezone(){
		date_default_timezone_set($this->config['timezone']);
		return $this;
	}
	
	/**
	 * Configura o tipo do conteúdo e o charset da página
	 * 
	 * Dessa forma o charset já é definido no cabeçalho do HTTP, portanto, não
	 * há a necessidade de usar a meta tag do HTML para isso.
	 * 
	 * @return Config
	 */
	private function charset(){
		header('Content-Type: text/html; charset='.$this->config['charset']);
		mb_internal_encoding($this->config['charset']);
		return $this;
	}
	
	/**
	 * Configura a conexão com o banco de dados
	 * 
	 * No caso do MySQL, ele aceita, dentre outros, os charsets latin1 e utf8
	 * (escritos dessa forma).
	 * 
	 * Se o servidor do banco de dados for o mesmo de onde o sistema está
	 * hospedado, usar localhost.
	 * 
	 * @return Config
	 */
	private function banco(){
		$this->config['banco']['servidor'] = $this->config['banco'][$this->config['ambiente']]['servidor'];
		$this->config['banco']['usuario'] = $this->config['banco'][$this->config['ambiente']]['usuario'];
		$this->config['banco']['senha'] = $this->config['banco'][$this->config['ambiente']]['senha'];
		$this->config['banco']['nome'] = $this->config['banco'][$this->config['ambiente']]['nome'];
		
		return $this;
	}
	
	/**
	 * Configura o idioma do sistema
	 * 
	 * Caso não haja internacionalização no sistema, há apenas a definição de um
	 * idioma padrão.
	 * 
	 * @return Config
	 */
	private function idioma(){
		if(!isset($_SESSION['idioma'])){$_SESSION['idioma'] = '1';}
		
		if(isset($_GET['cfgIdioma'])){
			$_SESSION['idioma'] = $_GET['cfgIdioma'];
			header("Location: {$_SERVER['HTTP_REFERER']}");
			exit;
		}
		
		return $this;
	}
	
	/**
	 * Chama o arquivo de autoload criado pelo Composer
	 * 
	 * @return Config
	 */
	private function autoload(){
		require '../vendor/autoload.php';
		return $this;
	}
	
	/**
	 * Verifica a sessão do usuário
	 * 
	 * Lembrar que, em caso de haverem vários usuários compartilhando o mesmo
	 * nome de sessão no sistema, deve-se verificar se o usuário que está
	 * executando o procedimento tem permissão.
	 * 
	 * @api
	 * @param string $sessao
	 * @throws \Exception
	 */
	public static function trancar($sessao = 'usuario'){
		if(!isset($_SESSION[$sessao])){
			throw new \Exception('Você não tem permissão.');
		}
	}
}
