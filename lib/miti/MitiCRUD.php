<?php
class MitiCRUD{
	private $ar;
	private $ar2;
	private $campos;
	private $join='';
	private $order_by='';
	private $limit='';
	
	public function __construct($ar){
		$this->ar=$ar;
	}
	
	public function inserir($duplas){
		//banco
		$MitiBD=new MitiBD();
		
		$sql='insert into '.$this->ar->getTabela().'(';
		
		//construcao da string dos campos
		$campos=array();
		foreach($duplas as $i=>$v){$campos[]=$i;}
		$sql.=implode(',',$campos);
		
		$sql.=')values(';
		
		//construcao da string dos valores
		$values=array();
		
		foreach($duplas as $i=>$v){
			//validacoes
			if($this->ar->getAnulaveis()[$i]==false&&$v==''){throw new Exception('Informe um valor');}
			if(strlen($v)>$this->ar->getTamanhos()[$i]){throw new Exception('Limite de caractéres excedido');}
			
			//tratamentos
			if($this->ar->getTipos()[$i]!='number'){
				$MitiBD->escapar($v);
			
				if($v!='null'){
					$v='"'.$v.'"';
				}
			}else{
				settype($v,'int');
			}
			
			$values[]=$v;
		}
		$sql.=implode(',',$values);
		
		$sql.=')';
		
		//exit($sql);
		
		//requisicao
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
	}
	
	public function ler($filtros=array()){
		//banco
		$MitiBD=new MitiBD();
		
		//criacao do vetor
		$where=array();
		
		foreach($filtros as $i=>$v){
			//tratamentos
			$MitiBD->escapar($v);
			$v='"%'.$v.'%"';
			
			$where[]=$this->ar->getCampos()[$i].' like '.$v;
		}
		
		//construcao da string
		if(isset($where[0])==true){
			$where=implode(' and ',$where);
			$where=' where '.$where;
		}else{
			$where='';
		}
		
		$sql='
			select
				'.$this->campos.
			' from '.$this->ar->getTabela().
			$this->join.
			$where.
			$this->order_by.
			$this->limit
		;
		
		//exit($sql);
		
		//requisicao
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
		
		return $MitiBD;
	}
	
	public function alterar($duplas,$pk){
		//banco
		$MitiBD=new MitiBD();
		
		//construcao da string
		$sql='update '.$this->ar->getTabela().' set ';
		
		$atribuicoes=array();
		
		foreach($duplas as $i=>$v){
			//validacoes
			if($this->ar->getAnulaveis()[$i]==false&&$v==''){throw new Exception('Informe um valor');}
			if(strlen($v)>$this->ar->getTamanhos()[$i]){throw new Exception('Limite de caractéres excedido');}
			
			//tratamentos
			if($this->ar->getTipos()[$i]!='number'){
				$MitiBD->escapar($v);
			
				if($v!='null'){
					$v='"'.$v.'"';
				}
			}else{
				settype($v,'int');
			}
			
			$atribuicoes[]=$i.'='.$v;
		}
		
		$sql.=implode(',',$atribuicoes);
		
		if($this->ar->getPkTipo()!='number'){$pk='"'.$pk.'"';}
		$sql.=' where '.$this->ar->getPkCampo().'='.$pk;
		
		//exit($sql);
		
		//requisicao
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
	}
	
	public function deletar($valor){
		//banco
		$MitiBD=new MitiBD();
		
		//tratamentos
		if($this->ar->getPkTipo()!='number'){
			$MitiBD->escapar($valor);
			$valor='"'.$valor.'"';
		}else{
			settype($valor,'int');
		}
		
		//requisicao
		$sql='delete from '.$this->ar->getTabela().' where '.$this->ar->getPkCampo().'='.$valor;
		
		//exit($sql);
		
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
	}
	
	public function definirCampos($campos,$campos2=array()){
		//criacao da string da pk
		$campos_ar=array();
		foreach($campos as $v){$campos_ar[]=$this->ar->getTabela().'.'.$v;}
		$campos_ar=implode(',',$campos_ar);
		
		//criacao da string da fk
		$campos_ar2=array();
		
		foreach($campos2 as $v){
			$campos_ar2[]=$this->ar2->getTabela().'.'.$v.
			' as '.$this->ar2->getTabela().'_'.$v;
		}
		
		$campos_ar2=implode(',',$campos_ar2);
		
		//pk + fk
		if($campos_ar2!=''){$campos=$campos_ar.','.$campos_ar2;}
		
		//exit($campos);
		
		//atribuicao
		$this->campos=$campos;
	}
	
	public function juntar($ar2,$chave,$chave2){
		//novo objeto
		$this->ar2=$ar2;
		
		//construcao da string
		$join='
			join '.$this->ar2->getTabela().
				' on '.$this->ar->getTabela().'.'.$chave.
				'='.$this->ar2->getTabela().'.'.$chave2
		;
		
		//exit($join);
		
		$this->join=$join;
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
}
?>
