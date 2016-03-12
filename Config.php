<?php
/**
 * Miti API, 2014 - 2016
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Configuração do sistema
 * 
 * Esse arquivo deve ser requerido, e a classe deve ser instanciada, no começo
 * de todas as páginas do sistema.
 * 
 * Como é nessa classe que é definida a função de autoload, ela é a única em que
 * o arquivo deve ser requerido manualmente.
 */
class Config{
	/**
	 * @var mixed[]
	 */
	private $config = [];
	
	/**
	 * Chama todos os métodos da classe
	 * 
	 * É aqui que se parametriza algumas configurações do sistema, o que é feito
	 * por página.
	 * 
	 * @param string $Classe Nome da classe responsável por tratar requisições
	 * na página. Com isso, apenas uma classe, no máximo, fica responsável por
	 * página. Caso tenha o valor vazio, não haverá classe que trate requisições.
	 * 
	 * @param bool $restrito Define se a página é restrita ao acesso, ou seja,
	 * se apenas pode ser acessada se o usuário possuir uma sessão ativa.
	 * 
	 * @param string $sessao Nome da sessão do usuário.
	 */
	public function __construct($Classe = null, $restrito = false, $sessao = 'usuario'){
		$this
			->config()
			->erro()
			->sistema()
			->timezone()
			->charset()
			->raiz()
			->banco()
			->sessao($restrito, $sessao)
			->idioma()
			->autoload()
			->requisicao($Classe)
		;
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
		$this->config['versao'] = '1.36';
		$this->config['timezone'] = 'America/Sao_Paulo';
		$this->config['charset'] = 'UTF-8';
		$this->config['salt'] = '$1$mitiapim$';
		
		$this->config['raiz'][0] = '/';
		$this->config['raiz'][1] = '/MitiAPI';
		
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
	 * Configura os caminhos para o diretório raiz do sistema
	 * 
	 * Tanto da perspectiva do sistema operacional, quanto da internet.
	 * 
	 * @return Config
	 */
	private function raiz(){
		$this->config['raizOS'] = $_SERVER['DOCUMENT_ROOT'].$this->config['raiz'][$this->config['ambiente']];
		$this->config['raizWEB'] = "http://{$_SERVER['HTTP_HOST']}{$this->config['raiz'][$this->config['ambiente']]}";
		
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
	 * Verifica a sessão do usuário
	 * 
	 * Deve-se escolher o local de destino do redirecionamento em caso de
	 * restrição, pois o que está por padrão pode, facilmente, não ser o desejado.
	 * 
	 * @param bool $restrito
	 * @param string $sessao
	 * @return Config
	 */
	private function sessao($restrito, $sessao){
		session_start();
		
		if($restrito && !isset($_SESSION[$sessao])){
			$_SESSION['status'] = 'Você não está autenticado.';
			header('Location: '.$this->config['raizWEB']);
			exit;
		}
		
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
	 * Verifica a sessão do usuário
	 * 
	 * Chamar esse método antes de todos os métodos que precisem de uma sessão ativa
	 * para serem executados, visto que podem existir páginas que não estejam
	 * fechadas para a sessão, e que podem estar configuradas para receberem
	 * requisições para determinada classe. É uma segunda camada de proteção.
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
	
	/**
	 * Chama o arquivo de autoload criado pelo Composer
	 * 
	 * @return Config
	 */
	private function autoload(){
		require 'vendor/autoload.php';
		return $this;
	}
	
	/**
	 * Configura o recebimento de requisições na página
	 * 
	 * Esse procedimento pretende ser genérico o suficiente para todas as
	 * situações.
	 * 
	 * Ele é ativado caso exista uma variável de nome "metodo" na requisição.
	 * 
	 * A classe requisitada é a que responde pela página.
	 * 
	 * Em caso de sucesso, atendendo ao design pattern POST/Redirect/GET
	 * ({@link http://en.wikipedia.org/wiki/Post/Redirect/Get}), ele gera uma
	 * requisição GET. Em caso de erro, ele não redireciona, para que as
	 * informações da requisição não sejam perdidas.
	 * 
	 * A variável "url" da requisição é a que define para onde será feita a
	 * requisição GET, em caso de sucesso.
	 * 
	 * @param string $Classe
	 * @return Config
	 */
	private function requisicao($Classe){
		if(isset($_REQUEST['metodo'])){
			$requisicao = $this->tratarRequisicao();
			
			try{
				$Objeto = new $Classe;
				$_SESSION['status'] = $Objeto->$_REQUEST['metodo']($requisicao, $_FILES);
				header("Location: {$_REQUEST['url']}");
				exit;
			}catch(\Exception $ex){$_SESSION['status'] = $ex->getMessage();}
		}
		
		return $this;
	}
	
	/**
	 * Trata as variáveis da requisição
	 * 
	 * As variáveis "metodo" e "url" são eliminadas para que o parâmetro passado
	 * ao método tenha apenas valores importantes à ele.
	 */
	private function tratarRequisicao(){
		if(!isset($_REQUEST['url'])){$_REQUEST['url'] = $_SERVER['HTTP_REFERER'];}
		$requisicao = $_SERVER['REQUEST_METHOD'] === 'POST'? $_POST: $_GET;
		
		unset($requisicao['metodo']);
		unset($requisicao['url']);
		
		return $requisicao;
	}
}
