<?php
class MitiCRUD{
	private $ar;
	private $campos;
	private $order_by;
	private $limit;
	
	public function __construct($ar){
		$this->ar=$ar;
	}
	
	public function inserir($duplas){
		//banco
		$MitiBD=new MitiBD();
		
		$sql='insert into '.$this->ar->getTabela().'(';
		
		//construcao da string dos campos
		$campos=array();
		foreach($duplas as $i=>$v){$campos[]=$this->ar->getCampos()[$i];}
		$sql.=implode(',',$campos);
		
		$sql.=')values(';
		
		//construcao da string dos valores
		$values=array();
		
		foreach($duplas as $i=>$v){
			//validacoes
			if($this->ar->getAnulaveis()[$i]==false&&$v==''){throw new Exception('Informe um valor');}
			if(strlen($v)>$this->ar->getTamanhos()[$i]){throw new Exception('Limite de caractéres excedido');}
			
			//tratamentos
			if($this->ar->getTipos()[$i]!='int'){
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
				'.$this->campos.'
			from '.$this->ar->getTabela().
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
	
	public function alterar($duplas,$valor){
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
			if($this->ar->getTipos()[$i]!='int'){
				$MitiBD->escapar($v);
			
				if($v!='null'){
					$v='"'.$v.'"';
				}
			}else{
				settype($v,'int');
			}
			
			$atribuicoes[]=$this->ar->getCampos()[$i].'='.$v;
		}
		
		$sql.=implode(',',$atribuicoes);
		
		if($this->ar->getPkTipo()!='int'){$valor='"'.$valor.'"';}
		$sql.=' where '.$this->ar->getPkCampo().'='.$valor;
		
		//exit($sql);
		
		//requisicao
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
	}
	
	public function deletar($valor){
		//banco
		$MitiBD=new MitiBD();
		
		//tratamentos
		if($this->ar->getPkTipo()!='int'){
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
}
?>
