<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Abstra��o de conex�o com banco de dados
 * 
 * Atualmente apenas interage com o MySQL, mas permite uma futura rela��o
 * com outros bancos. Respons�vel por conectar no banco, executar querys,
 * retornar id auto incrementado de inser��es, quantidade de registros afetados,
 * tratar dados, etc.
 * 
 * Preferencialmente utilizada atrav�s de um ORM.
 */
class Banco{
	/**
	 * @var Object
	 */
	private $Conexao;
	
	/**
	 * @var Object Result set de uma requisi��o.
	 */
	private $Requisicao;
	
	/**
	 * @var int Quantidade de registros afetados por uma requisi��o.
	 */
	private $afetados;
	
	/**
	 * @var int Id auto incrementado da �ltima inser��o.
	 */
	private $id;
	
	/**
	 * Abre a conex�o com o banco
	 * 
	 * Os par�metros recebem, por padr�o, constantes que, preferencialmente,
	 * s�o declaradas em um arquivo de configura��o. Caso haja a necessidade
	 * de uma conex�o com diferentes par�metros, basta realizar uma nova
	 * inst�ncia e inform�-los.
	 * 
	 * H� uma supress�o de erro (@) no instanciamento da conex�o por causa de
	 * problema no teste unit�rio.
	 * 
	 * As transa��es s�o configuradas para n�o serem cometidas automaticamente.
	 * 
	 * @api
	 * 
	 * @param string $servidor Nome do servidor do banco, caso seja a mesma
	 * m�quina, informar localhost.
	 * 
	 * @param string $usuario
	 * @param string $senha
	 * @param string $banco
	 * @param string $charset
	 */
	public function __construct($servidor = CFG_BANCO_SERVIDOR, $usuario = CFG_BANCO_USUARIO, $senha = CFG_BANCO_SENHA, $banco = CFG_BANCO_NOME, $charset = CFG_BANCO_CHARSET){
		$this->verificarExistenciaDaExtensao();
		$this->Conexao = @new \mysqli($servidor, $usuario, $senha, $banco);
		$this->verificarErroDeConexao();
		$this->definirCharset($charset);
		$this->Conexao->autocommit(false);
	}
	
	/**
	 * Verifica a exist�ncia da extens�o do PHP para trabalhar com o banco de dados
	 * 
	 * @throws \Exception Se a extens�o do PHP necess�ria n�o tiver sido carregada.
	 */
	private function verificarExistenciaDaExtensao(){
		if(!in_array('mysqli', get_loaded_extensions())){
			throw new \Exception('A classe '.__CLASS__.' depende da extens�o mysqli.');
		}
	}
	
	/**
	 * Verifica erro ao conectar no banco
	 * 
	 * Se o ambiente estiver configurado para imprimir os erros do PHP, ou seja,
	 * caso seja um ambiente de desenvolvimento, � lan�ada uma mensagem de erro
	 * t�cnica, sen�o, � lan�ada uma mensagem gen�rica.
	 * 
	 * Geralmente acontece por causa da configura��o do ambiente: diferentes
	 * m�quinas de desenvolvimento, ambiente de teste, ou de produ��o.
	 * 
	 * @throws \Exception
	 */
	private function verificarErroDeConexao(){
		if($this->Conexao->connect_error){
			$mensagem = ini_get('display_errors')?
				$this->Conexao->connect_error:
				'N�o foi poss�vel conectar ao banco de dados.'
			;
			
			throw new \Exception($mensagem);
		}
	}
	
	/**
	 * Define o charset da conex�o
	 * 
	 * @param string $charset O valor compat�vel com iso-8859-1 � latin1.
	 * 
	 * @throws \Exception Muito raro de acontecer.
	 */
	private function definirCharset($charset){
		if(!$this->Conexao->set_charset($charset)){
			throw new \Exception('Houve um erro ao definir o charset.');
		}
	}
	
	/**
	 * Escapa os dados da forma mais indicada para cada banco
	 * 
	 * Seu principal motivo, se n�o o �nico, � evitar SQL Injection. � necess�rio
	 * quando n�o se usa PDO, por n�o ser poss�vel diferenciar instru��o SQL de
	 * dados.
	 * 
	 * @api
	 * @param string[]|string $valores Dados de uma fonte n�o confi�vel.
	 * @return string[]|string
	 */
	public function escapar($valores){
		return is_array($valores)? $this->escaparArray($valores): $this->escaparString($valores);
	}
	
	private function escaparArray(array $valores){
		foreach($valores as $i => $valor){
			$valores[$i] = $this->Conexao->real_escape_string($valor);
		}
		
		return $valores;
	}
	
	private function escaparString($valor){
		return $this->Conexao->real_escape_string($valor);
	}
	
	/**
	 * Faz uma requisi��o ao banco
	 * 
	 * @api
	 * @param string $sql Recomenda-se o uso de um ORM para a montagem do SQL.
	 * @return Banco
	 */
	public function requisitar($sql){
		$this->Requisicao = $this->Conexao->query($sql);
		$this->verificarErroRequisicao($sql)->setAfetados()->setId();
		return $this;
	}
	
	/**
	 * Verifica se houve erro na requisi��o
	 * 
	 * A mensagem ser� t�cnica ou gen�rica baseado na configura��o do PHP sobre
	 * a impress�o de erros na tela. Mesma regra da mensagem de erro da conex�o.
	 * 
	 * @return Banco
	 * @throws \Exception
	 */
	private function verificarErroRequisicao($sql){
		if($this->Conexao->error){
			$mensagem = ini_get('display_errors')?
				"{$this->Conexao->error} - $sql":
				'Houve um erro ao realizar a requisi��o.'
			;
			
			throw new \Exception($mensagem);
		}
		
		return $this;
	}
	
	/**
	 * Define a quantidade de registros afetados
	 * 
	 * @return Banco
	 */
	private function setAfetados(){
		$this->afetados = $this->Conexao->affected_rows;
		return $this;
	}
	
	public function getAfetados(){
		return $this->afetados;
	}
	
	/**
	 * Define o id auto incrementado da �ltima inser��o
	 * 
	 * @return Banco
	 */
	private function setId(){
		$this->id = $this->Conexao->insert_id;
		return $this;
	}
	
	public function getId(){
		return $this->id;
	}
	
	/**
	 * Comete a �ltima transa��o aberta
	 * 
	 * � necess�rio sempre ser chamado ap�s cada requisi��o que altere dados
	 * no banco. Caso contr�rio, a transa��o n�o persiste. Vale lembrar que n�o
	 * � necess�rio em requisi��es de sele��o.
	 * 
	 * � o �ltimo m�todo � ser chamado na corrente ao usar o padr�o Fluent
	 * Interface.
	 * 
	 * A real utilidade dessa estrat�gia � quando se faz m�ltiplas requisi��es
	 * em um mesmo procedimento. Caso as primeiras sejam bem sucedidas, mas as
	 * �ltimas n�o, nenhuma � persistida, porque haveria um lan�amento de
	 * excec�o antes do script chegar � linha do cometimento.
	 * 
	 * @api
	 */
	public function cometer(){
		$this->Conexao->commit();
	}
	
	/**
	 * Rebobina a �ltima transa��o aberta
	 * 
	 * As transa��es s�o rebobinadas automaticamente ao final dos processos. Essa
	 * chamada � necess�ria quando queira-se rebobinar antes do final do processo.
	 * 
	 * @api
	 */
	public function rebobinar(){
		$this->Conexao->rollback();
	}
	
	/**
	 * Retorna o �ltimo result set em forma de array associativo
	 * 
	 * Os �ndices desse vetor s�o os nomes dos campos do banco ou os aliases
	 * definidos na requisi��o. Pode e deve ser iterado para o acesso � todos
	 * os registros quando a sele��o busca mais de uma linha.
	 * 
	 * @api
	 * @return string[]
	 */
	public function obterAssoc(){
		return $this->Requisicao->fetch_assoc();
	}
	
	/**
	 * Retorna o n�mero de linhas de uma sele��o
	 * 
	 * Diferente do getAfetados(), essa quantidade s� existe para sele��es,
	 * e n�o para todos os procedimentos do CRUD.
	 * 
	 * @api
	 * @return int
	 */
	public function obterQuantidade(){
		return $this->Requisicao->num_rows;
	}
	
	/**
	 * Retorna objetos que representam as caracter�sticas dos campos da tabela
	 * selecionada
	 * 
	 * Crucial para fazer um ORM autom�tico.
	 * 
	 * @api
	 * @return Object[]
	 */
	public function obterCampos(){
		return $this->Requisicao->fetch_fields();
	}
	
	/**
	 * Fecha a conex�o com o banco
	 */
	public function __destruct(){
		$this->Conexao->close();
	}
}
