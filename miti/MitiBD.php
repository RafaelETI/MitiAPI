<?php
/**
 * MitiAPI, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Abstração da conexão com um banco de dados
 * 
 * Atualmente apenas interage com o MySQL, mas permite uma futura relação
 * com outros bancos. Responsável por conectar no banco, executar querys,
 * retornar id auto incrementado de inserções, tempo da requisição, quantidade
 * de registros afetados, tratar dados, etc.
 * 
 * Preferencialmente utilizada através de um ORM.
 */
class MitiBD{
	/**
	 * @var Object
	 */
	private $conexao;
	
	/**
	 * @var Object Result set de uma requisição.
	 */
	private $requisicao;
	
	/**
	 * @var string Tempo da requisição.
	 */
	private $tempo;
	
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
	 * Os parâmetros recebem, por padrão, constantes que, preferencialmente,
	 * são declaradas em um arquivo de configuração. Caso haja a necessidade
	 * de uma conexão com diferentes parâmetros, basta realizar uma nova
	 * instância e informá-los.
	 * 
	 * Há uma supressão de erro (@) no instanciamento da conexão por causa de
	 * problema no teste unitário.
	 * 
	 * As transações são configuradas para não serem cometidas automaticamente.
	 * 
	 * @api
	 * 
	 * @param string $servidor Nome do servidor do banco, caso seja a mesma
	 * máquina, informar localhost.
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
	 * caso seja um ambiente de desenvolvimento, é lançada uma mensagem de erro
	 * técnica, senão, é lançada uma mensagem genérica.
	 * 
	 * Geralmente acontece por causa da configuração do ambiente: diferentes
	 * máquinas de desenvolvimento, ambiente de teste, ou de produção.
	 * 
	 * @throws Exception Em caso de falha na conexão.
	 */
	private function verificarErroConexao(){
		if($this->conexao->connect_error){
			if(ini_get('display_errors')){
				$mensagem=$this->conexao->connect_error;
			}else{
				$mensagem='Não foi possível conectar ao banco de dados.';
			}
			
			throw new Exception($mensagem);
		}
	}
	
	/**
	 * Define o charset da conexão
	 * 
	 * @param string $charset O valor compatível com iso-8859-1 é latin1.
	 * 
	 * @throws Exception Em caso de falha na definição do charset. Muito raro
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
	 * Seu principal motivo, se não o único, é evitar SQL Injection. É necessário
	 * quando não se usa PDO, por não ser possível diferenciar instrução SQL de
	 * dados.
	 * 
	 * @api
	 * @param string[]|string $valores Dados de uma fonte não confiável.
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
	 * Faz uma requisição ao banco
	 * 
	 * A requisição é "englobada" por comandos que serão usados para medir seu
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
	 * Verifica se houve erro na requisição
	 * 
	 * A mensagem será técnica ou genérica baseado na configuração do PHP sobre
	 * a impressão de erros na tela. Mesma regra da mensagem de erro da conexão.
	 * 
	 * @throws Exception Em caso de falha na requisição.
	 * @return \MitiBD
	 */
	private function verificarErroRequisicao(){
		if($this->conexao->error){
			if(ini_get('display_errors')){
				$mensagem=$this->conexao->error;
			}else{
				$mensagem='Houve um erro ao realizar a requisição.';
			}
			
			throw new Exception($mensagem);
		}
		
		return $this;
	}
	
	/**
	 * Define o tempo da requisição
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
	 * Define o id auto incrementado da última inserção
	 * 
	 * @return \MitiBD
	 */
	private function setId(){
		$this->id=$this->conexao->insert_id;
		return $this;
	}
	
	/**
	 * Retorna o tempo da última requisição
	 * 
	 * @api
	 * @return string
	 */
	public function getTempo(){
		return $this->tempo;
	}
	
	/**
	 * Retorna a quantidade de registros afetados na última requisição
	 * 
	 * Esses registros são aqueles relacionados à qualquer tipo de requisição
	 * do CRUD.
	 * 
	 * @api
	 * @return int
	 */
	public function getAfetados(){
		return $this->afetados;
	}
	
	/**
	 * Retorna o id auto incrementado da última inserção
	 * 
	 * @api
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}
	
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
	 * 
	 * @api
	 */
	public function cometer(){
		$this->conexao->commit();
	}
	
	/**
	 * Rebobina a última transação aberta
	 * 
	 * Sua utilidade está em dúvida, visto que basta não cometer a transação
	 * para que ela seja rebobinada automaticamente.
	 * 
	 * @api
	 */
	public function rebobinar(){
		$this->conexao->rollback();
	}
	
	/**
	 * Retorna o último result set em forma de array associativo
	 * 
	 * Os índices desse vetor são os nomes dos campos do banco ou os aliases
	 * definidos na requisição. Pode e deve ser iterado para o acesso à todos
	 * os registros quando a seleção busca mais de uma linha.
	 * 
	 * @api
	 * @return string[]
	 */
	public function obterAssoc(){
		return $this->requisicao->fetch_assoc();
	}
	
	/**
	 * Retorna o número de linhas de uma seleção
	 * 
	 * Diferente do getAfetados(), essa quantidade só existe para seleções,
	 * e não para todos os procedimentos do CRUD.
	 * 
	 * @api
	 * @return int
	 */
	public function obterQuantidade(){
		return $this->requisicao->num_rows;
	}
	
	/**
	 * Retorna objetos que representam as características dos campos da tabela
	 * selecionada
	 * 
	 * Crucial para fazer um ORM automático.
	 * 
	 * @api
	 * @return Object[]
	 */
	public function obterCampos(){
		return $this->requisicao->fetch_fields();
	}
	
	/**
	 * Fecha a conexão com o banco
	 */
	public function __destruct(){
		$this->conexao->close();
	}
}
