<?php
/**
 * Miti API, 2014 - 2015
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Abstração de conexão com banco de dados
 * 
 * Atualmente apenas interage com o MySQL, mas permite uma futura relação
 * com outros bancos. Responsável por conectar no banco, executar querys,
 * retornar id auto incrementado de inserções, quantidade de registros afetados,
 * tratar dados, etc.
 * 
 * Preferencialmente utilizada através de um ORM.
 */
class Banco{
	/**
	 * @var Object
	 */
	private $Conexao;
	
	/**
	 * @var Object Result set de uma requisição.
	 */
	private $Requisicao;
	
	/**
	 * @var int Quantidade de registros afetados por uma requisição.
	 */
	private $afetados;
	
	/**
	 * @var int Id auto incrementado da última inserção.
	 */
	private $id;
	
	/**
	 * Abre a conexão com o banco
	 * 
	 * Há uma supressão de erro (@) no instanciamento da conexão por causa de
	 * problema no teste unitário.
	 * 
	 * As transações são configuradas para não serem cometidas automaticamente.
	 * 
	 * @param string $servidor Nome do servidor do banco, caso seja a mesma
	 * máquina, informar localhost.
	 * 
	 * @param string $usuario
	 * @param string $senha
	 * @param string $banco
	 * @param string $charset
	 */
	public function __construct(array $config){
		$this->verificarExistenciaDaExtensao();
		$this->Conexao = @new \mysqli($config['banco']['servidor'], $config['banco']['usuario'], $config['banco']['senha'], $config['banco']['nome']);
		$this->verificarErroDeConexao();
		$this->definirCharset($config['banco']['charset']);
		$this->Conexao->autocommit(false);
	}
	
	/**
	 * Verifica a existência da extensão do PHP para trabalhar com o banco de dados
	 * 
	 * @throws \RuntimeException
	 */
	private function verificarExistenciaDaExtensao(){
		if(!extension_loaded('mysqli')){throw new \RuntimeException('A classe '.__CLASS__.' depende da extensão mysqli.');}
	}
	
	/**
	 * Verifica erro ao conectar no banco
	 * 
	 * Se o ambiente estiver configurado para imprimir os erros do PHP, ou seja,
	 * caso seja um ambiente de desenvolvimento, é lançada uma mensagem de erro
	 * técnica, senão, é lançada uma mensagem genérica.
	 * 
	 * Geralmente acontece por causa da configuração do ambiente: diferentes
	 * máquinas de desenvolvimento, ambiente de teste, ou de produção.
	 * 
	 * @throws \RuntimeException
	 */
	private function verificarErroDeConexao(){
		if($this->Conexao->connect_error){
			$mensagem = ini_get('display_errors')? $this->Conexao->connect_error: 'Não foi possível conectar ao banco de dados.';
			throw new \RuntimeException($mensagem);
		}
	}
	
	/**
	 * Define o charset da conexão
	 * 
	 * @param string $charset O valor compatível com iso-8859-1 é latin1.
	 * 
	 * @throws \DomainException Muito raro de acontecer.
	 */
	private function definirCharset($charset){
		if(!$this->Conexao->set_charset($charset)){throw new \DomainException('Houve um erro ao definir o charset.');}
	}
	
	/**
	 * Escapa os dados da forma mais indicada para cada banco
	 * 
	 * Seu principal motivo, se não o único, é evitar SQL Injection. É necessário
	 * quando não se usa PDO, por não ser possível diferenciar instrução SQL de
	 * dados.
	 * 
	 * @param string[]|string $valores Dados de uma fonte não confiável.
	 * 
	 * @return string[]|string
	 */
	public function escapar($valores){
		return is_array($valores)? $this->escaparArray($valores): $this->escaparString($valores);
	}
	
	private function escaparArray(array $valores){
		foreach($valores as &$valor){$valor = $this->escaparString($valor);}
		return $valores;
	}
	
	private function escaparString($valor){return $this->Conexao->real_escape_string($valor);}
	
	/**
	 * Faz uma requisição ao banco
	 * 
	 * @param string $sql Recomenda-se o uso de um ORM para a montagem do SQL.
	 * 
	 * @return Banco
	 */
	public function requisitar($sql){
		$this->Requisicao = $this->Conexao->query($sql);
		
		$this->verificarErroDeRequisicao($sql);
		$this->setAfetados();
		$this->setId();
		
		return $this;
	}
	
	/**
	 * Verifica se houve erro na requisição
	 * 
	 * A mensagem será técnica, específica, ou genérica, baseado na configuração
	 * do PHP sobre a impressão de erros na tela, e no código do erro.
	 * 
	 * @return Banco
	 * @throws \UnexpectedValueException
	 */
	private function verificarErroDeRequisicao($sql){
		if($this->Conexao->error){
			if(ini_get('display_errors')){
				$mensagem = "#{$this->Conexao->errno} {$this->Conexao->error} - $sql";
			}else{
				switch($this->Conexao->errno){
					case 1062: $mensagem = 'O registro já existe.'; break;
					default: $mensagem = "#{$this->Conexao->errno} Houve um erro ao realizar a requisição."; break;
				}
			}
			
			throw new \UnexpectedValueException($mensagem);
		}
		
		return $this;
	}
	
	/**
	 * Define a quantidade de registros afetados
	 * 
	 * @return Banco
	 */
	private function setAfetados(){$this->afetados = $this->Conexao->affected_rows;}
	public function getAfetados(){return $this->afetados;}
	
	/**
	 * Define o id auto incrementado da última inserção
	 * 
	 * @return Banco
	 */
	private function setId(){$this->id = $this->Conexao->insert_id;}
	public function getId(){return $this->id;}
	
	/**
	 * Comete a última transação aberta
	 * 
	 * É necessário sempre ser chamado após cada requisição que altere dados
	 * no banco. Caso contrário, a transação não persiste. Vale lembrar que não
	 * é necessário em requisições de seleção.
	 * 
	 * É o último método à ser chamado na corrente ao usar o padrão Fluent
	 * Interface.
	 * 
	 * A real utilidade dessa estratégia é quando se faz múltiplas requisições
	 * em um mesmo procedimento. Caso as primeiras sejam bem sucedidas, mas as
	 * últimas não, nenhuma é persistida, porque haveria um lançamento de
	 * excecão antes do script chegar à linha do cometimento.
	 */
	public function cometer(){$this->Conexao->commit();}
	
	/**
	 * Rebobina a última transação aberta
	 * 
	 * As transações são rebobinadas automaticamente ao final dos processos. Essa
	 * chamada é necessária quando queira-se rebobinar antes do final do processo.
	 */
	public function rebobinar(){$this->Conexao->rollback();}
	
	/**
	 * Retorna o último result set em forma de array associativo
	 * 
	 * Os índices desse vetor são os nomes dos campos do banco ou os aliases
	 * definidos na requisição. Pode e deve ser iterado para o acesso à todos
	 * os registros quando a seleção busca mais de uma linha.
	 * 
	 * @return string[]
	 */
	public function vetorizar(){return $this->Requisicao->fetch_assoc();}
	
	/**
	 * Retorna o número de linhas de uma seleção
	 * 
	 * Diferente do getAfetados(), essa quantidade só existe para seleções,
	 * e não para todos os procedimentos do CRUD.
	 * 
	 * @return int
	 */
	public function quantificar(){return $this->Requisicao->num_rows;}
	
	/**
	 * Retorna objetos que representam as características dos campos da tabela
	 * selecionada
	 * 
	 * Crucial para fazer um ORM automático.
	 * 
	 * @return Object[]
	 */
	public function mapear(){return $this->Requisicao->fetch_fields();}
	
	/**
	 * Fecha a conexão com o banco
	 */
	public function __destruct(){$this->Conexao->close();}
}
