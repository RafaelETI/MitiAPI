<?php
class MitiBD{
	private $conexao;
	private $requisicao;
	private $tempo;
	private $afetados;
	private $id;
	
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
	}
	
	private function verificarErroConexao(){
		if($this->conexao->connect_error){
			$mensagem=ini_get('display_errors')?
				$this->conexao->connect_error:
				'Não foi possível conectar ao banco de dados'
			;
			
			throw new Exception($mensagem);
		}
	}
	
	private function definirCharset($charset){
		if(!$this->conexao->set_charset($charset)){
			throw new Exception('Houve um erro ao definir o charset');
		}
	}
	
	public function escapar($valores){
		if(is_array($valores)){
			return $this->escaparArray($valores);
		}else{
			return $this->escaparString($valores);
		}
	}
	
	private function escaparArray($valores){
		foreach($valores as $i=>$v){
			$valores[$i]=$this->conexao->real_escape_string($v);
		}
		
		return $valores;
	}
	
	private function escaparString($valores){
		return $this->conexao->real_escape_string($valores);
	}
	
	public function requisitar($sql){
		$micro=array(microtime(true));
		$this->requisicao=$this->conexao->query($sql);
		$micro[1]=microtime(true);
		
		$this->verificarErroRequisicao();
		$this->setTempo($micro);
		$this->setAfetados();
		$this->setId();
		
		return $this;
	}
	
	private function verificarErroRequisicao(){
		if($this->conexao->error){
			$erro='Houve um erro ao realizar a requisição';
			$mensagem=ini_get('display_errors')?$this->conexao->error:$erro;
			throw new Exception($mensagem);
		}
	}
	
	private function setTempo($micro){
		$MitiDesempenho=new MitiDesempenho;
		$this->tempo=$MitiDesempenho->medirTempoExecucao($micro);
	}
	
	private function setAfetados(){
		$this->afetados=$this->conexao->affected_rows;
	}
	
	private function setId(){
		$this->id=$this->conexao->insert_id;
	}

	public function getTempo(){
		return $this->tempo;
	}
	
	public function getAfetados(){
		return $this->afetados;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function obterAssoc(){
		return $this->requisicao->fetch_assoc();
	}
	
	public function obterQuantidade(){
		return $this->requisicao->num_rows;
	}
	
	public function obterCampos(){
		return $this->requisicao->fetch_fields();
	}
	
	public function __destruct(){
		$this->conexao->close();
	}
}
