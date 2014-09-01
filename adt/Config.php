<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace adt;

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
	private $config=array();
	
	/**
	 * Chama todos os métodos da classe
	 * 
	 * É aqui que se parametriza algumas configurações do sistema, o que é feito
	 * por página.
	 * 
	 * @api
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
	public function __construct($Classe,$restrito,$sessao='usuario'){
		$this
			->config()
			->ambiente()
			->erro()
			->sistema()
			->timezone()
			->charset()
			->salt()
			->raiz()
			->banco()
			->sessao($restrito,$sessao)
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
		$this->config['ambiente']=1;
		$this->config['sistema']='Miti API';
		$this->config['versao']='1.5.16';
		$this->config['timezone']='America/Sao_Paulo';
		$this->config['charset']='iso-8859-1';
		$this->config['salt'] = '$1$skazicom$';
		
		$this->config['raiz'][0]='';
		$this->config['raiz'][1]='MitiAPI';
		
		$this->config['banco'][0]=array(
			'servidor'=>'',
			'usuario'=>'',
			'senha'=>'',
			'banco'=>'',
			'charset'=>'',
		);
		
		$this->config['banco'][1]=array(
			'servidor'=>'',
			'usuario'=>'',
			'senha'=>'',
			'banco'=>'',
			'charset'=>'',
		);
		
		return $this;
	}
	
	/**
	 * Configura o ambiente do sistema
	 * 
	 * Por convenção, o ambiente de produção é o de valor zero, e os outros são
	 * incrementados em um!
	 * 
	 * Há a intenção de que esse seja o único ponto de manutenção ao trocar o
	 * sistema de ambiente. Muito cuidado para não enviar esse arquivo para a
	 * produção, estando configurado para o desenvolvimento! Nesse caso, muito
	 * provável que as configurações de banco de dados estejam erradas e os
	 * erros sejam mostrados na tela. Sempre conferir o sistema on-line quando
	 * subir esse arquivo.
	 * 
	 * O mais importante da declaração dessa constante é o seu docblock, além da
	 * facilidade de leitura nos métodos seguintes.
	 * 
	 * @return Config
	 */
	private function ambiente(){
		define('CFG_AMBIENTE', $this->config['ambiente']);
		return $this;
	}
	
	/**
	 * Configura como o PHP trata os erros do sistema
	 * 
	 * É uma das principais configurações de segurança. Nunca mostre os erros
	 * emitidos pelo PHP diretamente na tela, no ambiente de produção!
	 * 
	 * @return Config
	 */
	private function erro(){
		error_reporting(-1);
		
		if(CFG_AMBIENTE === 0){
			ini_set('display_errors',0);
			ini_set('display_startup_errors',0);
		}else{
			ini_set('display_errors',1);
			ini_set('display_startup_errors',1);
		}
		
		return $this;
	}
	
	/**
	 * Configura o nome e a versão do sistema
	 * 
	 * Recomenda-se chamar essa constante em algum lugar visível de todas as
	 * interfaces para que possa-se identificar facilmente se o sistema está
	 * configurado para o ambiente de produção ou não.
	 * 
	 * @return Config
	 */
	private function sistema(){
		if(CFG_AMBIENTE === 0){
			define('CFG_SISTEMA', $this->config['sistema']);
		}else{
			define('CFG_SISTEMA', "{$this->config['sistema']} {$this->config['versao']}");
		}
		
		return $this;
	}
	
	/**
	 * Configura a timezone do PHP
	 * 
	 * Vide a lista de timezones que o PHP suporta:
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
		define('CFG_CHARSET', $this->config['charset']);
		header('Content-Type: text/html; charset='.CFG_CHARSET);
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
	 * Configura os caminhos para o diretório raiz do sistema
	 * 
	 * Tanto da perspectiva do sistema operacional, quanto da internet.
	 * 
	 * Configura-se uma string que não seja vazia caso o sistema não esteja na
	 * raíz do diretório web, mas em um subdiretório.
	 * 
	 * @return Config
	 */
	private function raiz(){
		define('CFG_RAIZ_OS', "{$_SERVER['DOCUMENT_ROOT']}/{$this->config['raiz'][CFG_AMBIENTE]}");
		define('CFG_RAIZ_WEB', "http://{$_SERVER['HTTP_HOST']}/{$this->config['raiz'][CFG_AMBIENTE]}");
		
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
		define('CFG_BANCO_SERVIDOR', $this->config['banco'][CFG_AMBIENTE]['servidor']);
		define('CFG_BANCO_USUARIO', $this->config['banco'][CFG_AMBIENTE]['usuario']);
		define('CFG_BANCO_SENHA', $this->config['banco'][CFG_AMBIENTE]['senha']);
		define('CFG_BANCO_NOME', $this->config['banco'][CFG_AMBIENTE]['banco']);
		define('CFG_BANCO_CHARSET', $this->config['banco'][CFG_AMBIENTE]['charset']);
		
		return $this;
	}
	
	/**
	 * Verifica a sessão do usuário, à nível de página
	 * 
	 * Deve-se escolher o local de destino do redirecionamento em caso de
	 * restrição, pois o que está por padrão pode, facilmente, não ser o desejado.
	 * 
	 * @param bool $restrito
	 * @param string $sessao
	 * @return Config
	 */
	private function sessao($restrito,$sessao){
		session_start();
		
		if($restrito&&!isset($_SESSION[$sessao])){
			$_SESSION['status']='Você não está autenticado.';
			header('Location: '.CFG_RAIZ_WEB);
			exit;
		}
		
		return $this;
	}
	
	/**
	 * Verifica a sessão do usuário, à nível de método
	 * 
	 * Chamar esse método em todos os métodos que precisem de uma sessão ativa
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
	public static function trancar($sessao='usuario'){
		if(!isset($_SESSION[$sessao])){
			throw new \Exception('Você não tem permissão.');
		}
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
	 * @return Config
	 */
	private function autoload(){
		spl_autoload_register(function($Classe){require CFG_RAIZ_OS."/$Classe.php";});
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
			$requisicao=$this->tratarRequisicao();
			
			try{
				$Objeto=new $Classe;
				$_SESSION['status']=$Objeto->$_REQUEST['metodo']($requisicao);
				header("Location: {$_REQUEST['url']}");
				exit;
			}catch(\Exception $e){
				$_SESSION['status']=$e->getMessage();
			}
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
		if(!isset($_REQUEST['url'])){
			$_REQUEST['url']=$_SERVER['HTTP_REFERER'];
		}
		
		$requisicao=$_REQUEST;
		unset($requisicao['metodo']);
		unset($requisicao['url']);
		
		return $requisicao;
	}
}
