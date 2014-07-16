<?php
/**
 * Miti API, 2014
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
	 * Chama todos os métodos de configuração da classe
	 * 
	 * É aqui que se parametriza algumas configurações do sistema, o que é feito
	 * por página.
	 * 
	 * @api
	 * 
	 * @param string $Classe Nome da classe responsável por tratar requisições
	 * na página. Com isso, apenas uma classe, no máximo, fica responsável por
	 * página. Caso tenha o valor vazio, a página não trata nenhuma requisição.
	 * 
	 * @param bool $restrito Define se a página é restrita ao acesso, ou seja,
	 * se apenas pode ser acessada se o usuário possuir uma sessão ativa.
	 * 
	 * @param string $sessao Nome da sessão.
	 */
	public function __construct($Classe,$restrito,$sessao='login'){
		$this
			->ambiente()
			->sistema()
			->banco()
			->erro()
			->timezone()
			->charset()
			->raiz()
			->sessao($restrito,$sessao)
			->autoload()
			->requisicao($Classe)
		;
	}
	
	/**
	 * Configura o ambiente do sistema
	 * 
	 * Por convenção, o ambiente de produção é o de valor zero, e os outros são
	 * incrementados em um.
	 * 
	 * Por padrão essa classe tem definições para dois ambientes. Caso queira-se
	 * adicionar mais, deve-se adicionar novas configurações nos outros métodos
	 * cabíveis.
	 * 
	 * Há a intenção de que esse seja o único ponto de manutenção ao trocar o
	 * sistema de ambiente. Muito cuidado para não enviar esse arquivo para a
	 * produção, estando configurado para o desenvolvimento! Nesse caso, muito
	 * provável que as configurações de banco de dados estejam erradas e os
	 * erros sejam mostrados na tela. Sempre conferir o sistema on-line quando
	 * subir esse arquivo.
	 * 
	 * @return \Config
	 */
	private function ambiente(){
		define('AMBIENTE',1);
		return $this;
	}
	
	/**
	 * Configura o nome e a versão do sistema
	 * 
	 * Recomenda-se chamar essa constante em algum lugar visível de todas as
	 * interfaces para que possa-se identificar facilmente se o sistema está
	 * configurado para o ambiente de produção ou não.
	 * 
	 * @return \Config
	 */
	private function sistema(){
		if(AMBIENTE===0){
			define('SISTEMA','Miti API');
		}else{
			define('SISTEMA','Miti API 1.1.9');
		}
		
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
	 * @return \Config
	 */
	private function banco(){
		if(AMBIENTE===0){
			define('BD_SERVIDOR','servidor');
			define('BD_USUARIO','usuario');
			define('BD_SENHA','senha');
			define('BD_BANCO','banco');
			define('BD_CHARSET','charset');
		}else if(AMBIENTE===1){
			define('BD_SERVIDOR','servidor');
			define('BD_USUARIO','usuario');
			define('BD_SENHA','senha');
			define('BD_BANCO','banco');
			define('BD_CHARSET','charset');
		}
		
		return $this;
	}
	
	/**
	 * Configura como o PHP trata os erros do sistema
	 * 
	 * É uma das principais configurações de segurança. Nunca mostre os erros
	 * emitidos pelo PHP diretamente na tela, no ambiente de produção!
	 * 
	 * @return \Config
	 */
	private function erro(){
		error_reporting(-1);
		
		if(AMBIENTE===0){
			ini_set('display_errors',0);
		}else{
			ini_set('display_errors',1);
		}
		
		return $this;
	}
	
	/**
	 * Configura a timezone do PHP
	 * 
	 * Vide a lista de timezones que o PHP suporta:
	 * {@link http://php.net/manual/en/timezones.php}.
	 * 
	 * @return \Config
	 */
	private function timezone(){
		date_default_timezone_set('America/Sao_Paulo');
		return $this;
	}
	
	/**
	 * Configura o tipo do conteúdo e o charset da página
	 * 
	 * Dessa forma o charset já é definido no cabeçalho do HTTP, portanto, não
	 * há a necessidade de usar a meta tag do HTML para isso.
	 * 
	 * @return \Config
	 */
	private function charset(){
		header('content-type:text/html; charset=iso-8859-1');
		return $this;
	}
	
	/**
	 * Configura os caminhos para o diretório raiz do sistema
	 * 
	 * Tanto da perspectiva do sistema operacional, quanto da internet.
	 * 
	 * Para o ambiente de produção, caso o sistema não esteja na raiz do domínio,
	 * deve-se complementar o caminho para se chegar até ele no código.
	 * 
	 * @return \Config
	 */
	private function raiz(){
		if(AMBIENTE===0){
			define('RAIZ_OS',$_SERVER['DOCUMENT_ROOT'].'/');
			define('RAIZ_WEB','http://'.$_SERVER['HTTP_HOST'].'/');
		}else if(AMBIENTE===1){
			define('RAIZ_OS',$_SERVER['DOCUMENT_ROOT'].'/MitiAPI/');
			define('RAIZ_WEB','http://'.$_SERVER['HTTP_HOST'].'/MitiAPI/');
		}
		
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
	 * @return \Config
	 */
	private function sessao($restrito,$sessao){
		session_start();
		
		if($restrito&&!isset($_SESSION[$sessao])){
			$_SESSION['status']='Você não está autenticado.';
			header('location:'.RAIZ_WEB.'admin/index.php');
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
	public static function verificarSessao($sessao='login'){
		if(!isset($_SESSION[$sessao])){
			throw new Exception('Você não tem permissão.');
		}
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
	 * @return \Config
	 */
	private function autoload(){
		function mitiAutoload($Classe){
			foreach(array('adt','miti') as $v){
				if(file_exists(RAIZ_OS.$v.'/'.$Classe.'.php')){
					require RAIZ_OS.$v.'/'.$Classe.'.php';
					break;
				}
			}
		}
		
		spl_autoload_register('mitiAutoload');
		
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
	 * @return \Config
	 */
	private function requisicao($Classe){
		if(isset($_REQUEST['metodo'])){
			$this->tratarRequisicao();
			
			try{
				$Objeto=new $Classe;
				$Objeto->$_REQUEST['metodo']();
				header('location:'.$_REQUEST['url']);
				exit;
			}catch(Exception $e){
				$_SESSION['status']=$e->getMessage();
			}
		}
		
		return $this;
	}
	
	/**
	 * Trata as variáveis da requisição
	 * 
	 * As variáveis "metodo" e "url" são eliminadas para que as super globais
	 * $_POST e $_GET tenham apenas valores interessantes aos métodos requisitados.
	 */
	private function tratarRequisicao(){
		unset($_POST['metodo']);
		unset($_POST['url']);
		unset($_GET['metodo']);
		unset($_GET['url']);
		
		if(!isset($_REQUEST['url'])){
			$_REQUEST['url']=$_SERVER['HTTP_REFERER'];
		}
	}
}
