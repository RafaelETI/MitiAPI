<?php
class MitiBD{
	private $conexao;
	private $requisicao;
	private $tempo;
	private $afetados;
	private $id;
	
	public function __construct($servidor=BD_SERVIDOR,$usuario=BD_USUARIO,$senha=BD_SENHA,$banco=BD_BANCO,$charset=BD_CHARSET){
		$this->conexao=new mysqli($servidor,$usuario,$senha,$banco);
		
		if($this->conexao->connect_error!=false){throw new Exception('Não foi possível conectar ao banco de dados');}
		if($this->conexao->set_charset($charset)==false){throw new Exception('Houve um erro ao definir o charset');}
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
	
	public function escapar(&$valores){
		if(is_array($valores)==false){
			$valores=$this->conexao->real_escape_string($valores);
		}else{
			foreach($valores as $i=>$v){$valores[$i]=$this->conexao->real_escape_string($v);}
		}
	}
	
	public function requisitar($sql){
		//requisicao
		$micro=array(microtime(true));
		$this->requisicao=$this->conexao->query($sql);
		$micro[1]=microtime(true);
		
		//desempenho
		$MitiDesempenho=new MitiDesempenho();
		$this->tempo=$MitiDesempenho->medirTempoExecucao($micro);
		
		//erro
		if($this->conexao->error!=false){throw new Exception('Houve um erro ao realizar a requisição');}
		
		//infos
		$this->afetados=$this->conexao->affected_rows;
		$this->id=$this->conexao->insert_id;
	}
	
	public function fechar(){
		$this->conexao->close();
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
}
?>
