<?php
/**
 * MitiAPI, 2014.
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 */
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
		$this->conexao->autocommit(false);
	}
	
	private function verificarErroConexao(){
		if($this->conexao->connect_error){
			if(ini_get('display_errors')){
				$mensagem=$this->conexao->connect_error;
			}else{
				$mensagem='Não foi possível conectar ao banco de dados';
			}
			
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
			$valores=$this->escaparArray($valores);
		}else{
			$valores=$this->escaparString($valores);
		}
		
		return $valores;
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
		
		$this
			->verificarErroRequisicao()
			->setTempo($micro)
			->setAfetados()
			->setId()
		;
		
		return $this;
	}
	
	private function verificarErroRequisicao(){
		if($this->conexao->error){
			if(ini_get('display_errors')){
				$mensagem=$this->conexao->error;
			}else{
				$mensagem='Houve um erro ao realizar a requisição';
			}
			
			throw new Exception($mensagem);
		}
		
		return $this;
	}
	
	private function setTempo($micro){
		$MitiDesempenho=new MitiDesempenho;
		$this->tempo=$MitiDesempenho->medirTempoExecucao($micro);
		return $this;
	}
	
	private function setAfetados(){
		$this->afetados=$this->conexao->affected_rows;
		return $this;
	}
	
	private function setId(){
		$this->id=$this->conexao->insert_id;
		return $this;
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
	
	public function cometer(){
		$this->conexao->commit();
	}
	
	public function rebobinar(){
		$this->conexao->rollback();
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
