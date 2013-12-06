<?php
class MitiBD{
	private $conexao;
	private $requisicao;
	
	public function __construct($servidor=BD_SERVIDOR,$usuario=BD_USUARIO,$senha=BD_SENHA,$banco=BD_BANCO){
		$this->conexao=new mysqli($servidor,$usuario,$senha,$banco);
		if($this->conexao->connect_error!=false){throw new Exception('Não foi possível conectar ao banco de dados');}
	}
	
	public function escapar(&$string){
		$MitiParcialidade=new MitiParcialidade();
		$MitiParcialidade->preparar($string);
		
		foreach($string as $i=>$v){
			if($MitiParcialidade->parcializar($v)==true){continue;}
	
			$string[$i]=$this->conexao->real_escape_string($v);
		}
		
		$MitiParcialidade->finalizar($string);
	}
	
	public function requisitar($sql){
		$this->requisicao=$this->conexao->query($sql);
		if($this->conexao->error!=false){throw new Exception('Houve um erro ao realizar a requisição');}
		if($this->conexao->affected_rows==0){throw new Exception('Nenhum registro foi afetado pela requisição');}
	}
	
	public function obterId(){
		return $this->conexao->insert_id;
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
}
?>
