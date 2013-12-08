<?php
class MitiCRUD{
	private $ar;
	private $campos;
	private $where;
	private $order_by;
	private $limit;
	
	public function __construct($ar){
		$this->ar=$ar;
	}
	
	public function inserir(){
		
	}
	
	public function definirCampos($indices){
		//criacao do vetor
		$campos=array();
		foreach($indices as $v){
			$campos[]=$this->ar->getCampos()[$v];
		}
		
		//construcao da string
		$campos=implode(',',$campos);
		
		//atribuicao
		$this->campos=$campos;
	}
	
	public function filtrar($duplas){
		//criacao do vetor
		$where=array();
		foreach($duplas as $i=>$v){
			if($this->ar->getTipos()[$i]!='int'){$v='"%'.$v.'%"';}
			$where[]=$this->ar->getCampos()[$i].' like '.$v;
		}
		
		//construcao da string
		if(isset($where[0])==true){
			$where=implode(' and ',$where);
			$where=' where '.$where;
		}else{
			$where='';
		}
		
		//atribuicao
		$this->where=$where;
	}
	
	public function ordenar($duplas){
		//criacao do vetor
		$order_by=array();
		foreach($duplas as $i=>$v){
			$order_by[]=$this->ar->getCampos()[$i].' '.$v;
		}
		
		//construcao da string
		if(isset($order_by[0])==true){
			$order_by=implode(',',$order_by);
			$order_by=' order by '.$order_by;
		}else{
			$order_by='';
		}
		
		//atribuicao
		$this->order_by=$order_by;
	}
	
	public function limitar($casas,$inicio=''){
		if($inicio!=''){$inicio.=',';}
		$limit=' limit '.$inicio.$casas;
		
		$this->limit=$limit;
	}
	
	public function ler(){
		$MitiBD=new MitiBD();
		
		$sql='
			select
				'.$this->campos.'
			from '.$this->ar->getTabela().
			$this->where.
			$this->order_by.
			$this->limit
		;
		
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
		
		return $MitiBD;
	}
	
	public function alterar(){
		
	}
	
	public function deletar($valor){
		$MitiBD=new MitiBD();
		if($this->ar->getPkTipo()!='int'){$valor='"'.$valor.'"';}
		$sql='delete from '.$this->ar->getTabela().' where '.$this->ar->getPkCampo().'='.$valor;
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
	}
}
?>
