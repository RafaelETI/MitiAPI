<?php
class MitiCRUD{
	private $ar;
	private $ar_fk;
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
			$i=$this->obterIndice($i);
		
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
	
	public function alterar($duplas,$valor){
		//banco
		$MitiBD=new MitiBD();
		
		//construcao da string
		$sql='update '.$this->ar->getTabela().' set ';
		
		$atribuicoes=array();
		
		foreach($duplas as $i=>$v){
			$campo=$i;
			$i=$this->obterIndice($i);
		
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
			
			$atribuicoes[]=$campo.'='.$v;
		}
		
		$sql.=implode(',',$atribuicoes);
		
		if($this->ar->getPkTipo()!='number'){$valor='"'.$valor.'"';}
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
	
	public function definirCampos($campos_pk,$campos_fk=array()){
		//criacao da string da pk
		$pks=array();
		foreach($campos_pk as $v){$pks[]=$this->ar->getTabela().'.'.$v;}
		$pks=implode(',',$pks);
		
		//criacao da string da fk
		$fks=array();
		
		foreach($campos_fk as $v){
			$fks[]=$this->ar_fk->getTabela().'.'.$v.
			' as '.$this->ar_fk->getTabela().'_'.$v;
		}
		
		$fks=implode(',',$fks);
		
		//pk + fk
		if($fks!=''){$campos=$pks.','.$fks;}
		
		//exit($campos);
		
		//atribuicao
		$this->campos=$campos;
	}
	
	public function juntar($ar,$pk,$fk){
		//novo objeto
		$this->ar_fk=$ar;
		
		//construcao da string
		$join='
			join '.$this->ar_fk->getTabela().
				' on '.$this->ar->getTabela().'.'.$pk.
				'='.$this->ar_fk->getTabela().'.'.$fk
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
	
	private function obterIndice($nome,$ar=null){
		if($ar==null){$ar=$this->ar;}
	
		foreach($ar->getCampos() as $i=>$v){
			if($v==$nome){return $i;}
		}
	}
}
?>
