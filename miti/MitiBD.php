<?php
/**
 * MitiAPI, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Abstra��o da conex�o com um banco de dados
 * 
 * Atualmente apenas interage com o MySQL, mas permite uma futura rela��o
 * com outros bancos. Respons�vel por conectar no banco, executar querys,
 * retornar id auto incrementado de inser��es, tempo da requisi��o, quantidade
 * de registros afetados, tratar dados, etc.
 * 
 * Preferencialmente utilizada atrav�s de um ORM.
 */
class MitiBD{
	/**
	 * @var Object
	 */
	private $conexao;
	
	/**
	 * @var Object Result set de uma requisi��o.
	 */
	private $requisicao;
	
	/**
	 * @var string Tempo da requisi��o.
	 */
	private $tempo;
	
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
	public function __construct(
		$servidor=BD_SERVIDOR,
		$usuario=BD_USUARIO,
		$senha=BD_SENHA,
		$banco=BD_BANCO,
		$charset=BD_CHARSET
	){
		$this->conexao=@new mysqli($servidor,$usuario,$senha,$banco);
		$this->verificarErroConexao();
		$this->definirCharset($charset);
		$this->conexao->autocommit(false);
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
	 * @throws Exception Em caso de falha na conex�o.
	 */
	private function verificarErroConexao(){
		if($this->conexao->connect_error){
			if(ini_get('display_errors')){
				$mensagem=$this->conexao->connect_error;
			}else{
				$mensagem='N�o foi poss�vel conectar ao banco de dados.';
			}
			
			throw new Exception($mensagem);
		}
	}
	
	/**
	 * Define o charset da conex�o
	 * 
	 * @param string $charset O valor compat�vel com iso-8859-1 � latin1.
	 * 
	 * @throws Exception Em caso de falha na defini��o do charset. Muito raro
	 * de acontecer.
	 */
	private function definirCharset($charset){
		if(!$this->conexao->set_charset($charset)){
			throw new Exception('Houve um erro ao definir o charset.');
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
		if(is_array($valores)){
			$valores=$this->escaparArray($valores);
		}else{
			$valores=$this->escaparString($valores);
		}
		
		return $valores;
	}
	
	/**
	 * Escapa um array
	 * 
	 * @param string[] $valores
	 * @return string[]
	 */
	private function escaparArray(array $valores){
		foreach($valores as $i=>$v){
			$valores[$i]=$this->conexao->real_escape_string($v);
		}
		
		return $valores;
	}
	
	/**
	 * Escapa uma string
	 * 
	 * @param string $valor
	 * @return string
	 */
	private function escaparString($valor){
		return $this->conexao->real_escape_string($valor);
	}
	
	/**
	 * Faz uma requisi��o ao banco
	 * 
	 * A requisi��o � "englobada" por comandos que ser�o usados para medir seu
	 * tempo.
	 * 
	 * @api
	 * @param string $sql Recomenda-se o uso de um ORM para a montagem do SQL.
	 * @return \MitiBD
	 */
	public function requisitar($sql){
		$microtimes=array(microtime(true));
		$this->requisicao=$this->conexao->query($sql);
		$microtimes[1]=microtime(true);
		
		$this
			->verificarErroRequisicao()
			->setTempo($microtimes)
			->setAfetados()
			->setId()
		;
		
		return $this;
	}
	
	/**
	 * Verifica se houve erro na requisi��o
	 * 
	 * A mensagem ser� t�cnica ou gen�rica baseado na configura��o do PHP sobre
	 * a impress�o de erros na tela. Mesma regra da mensagem de erro da conex�o.
	 * 
	 * @throws Exception Em caso de falha na requisi��o.
	 * @return \MitiBD
	 */
	private function verificarErroRequisicao(){
		if($this->conexao->error){
			if(ini_get('display_errors')){
				$mensagem=$this->conexao->error;
			}else{
				$mensagem='Houve um erro ao realizar a requisi��o.';
			}
			
			throw new Exception($mensagem);
		}
		
		return $this;
	}
	
	/**
	 * Define o tempo da requisi��o
	 * 
	 * @param float[] $microtimes
	 * @return \MitiBD
	 */
	private function setTempo($microtimes){
		$MitiDesempenho=new MitiDesempenho;
		$this->tempo=$MitiDesempenho->medirTempoExecucao($microtimes);
		return $this;
	}
	
	/**
	 * Define a quantidade de registros afetados
	 * 
	 * @return \MitiBD
	 */
	private function setAfetados(){
		$this->afetados=$this->conexao->affected_rows;
		return $this;
	}
	
	/**
	 * Define o id auto incrementado da �ltima inser��o
	 * 
	 * @return \MitiBD
	 */
	private function setId(){
		$this->id=$this->conexao->insert_id;
		return $this;
	}
	
	/**
	 * Retorna o tempo da �ltima requisi��o
	 * 
	 * @api
	 * @return string
	 */
	public function getTempo(){
		return $this->tempo;
	}
	
	/**
	 * Retorna a quantidade de registros afetados na �ltima requisi��o
	 * 
	 * Esses registros s�o aqueles relacionados � qualquer tipo de requisi��o
	 * do CRUD.
	 * 
	 * @api
	 * @return int
	 */
	public function getAfetados(){
		return $this->afetados;
	}
	
	/**
	 * Retorna o id auto incrementado da �ltima inser��o
	 * 
	 * @api
	 * @return int
	 */
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
		$this->conexao->commit();
	}
	
	/**
	 * Rebobina a �ltima transa��o aberta
	 * 
	 * Sua utilidade est� em d�vida, visto que basta n�o cometer a transa��o
	 * para que ela seja rebobinada automaticamente.
	 * 
	 * @api
	 */
	public function rebobinar(){
		$this->conexao->rollback();
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
		return $this->requisicao->fetch_assoc();
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
		return $this->requisicao->num_rows;
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
		return $this->requisicao->fetch_fields();
	}
	
	/**
	 * Fecha a conex�o com o banco
	 */
	public function __destruct(){
		$this->conexao->close();
	}
}
