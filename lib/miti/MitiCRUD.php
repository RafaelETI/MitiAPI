<?php
class MitiCRUD{
	private $ar;
	private $tipos;
	private $anulaveis;
	private $tamanhos;
	private $arx=array();
	private $campos;
	private $join='';
	private $order_by='';
	private $limit='';
	
	public function __construct($ar){
		$this->ar=$ar;
		$this->tipos=$ar->getTipos();
		$this->anulaveis=$ar->getAnulaveis();
		$this->tamanhos=$ar->getTamanhos();
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
			if($this->anulaveis[$i]==false&&$v==''){throw new Exception('Informe um valor');}
			if(strlen($v)>$this->tamanhos[$i]){throw new Exception('Limite de caractéres excedido');}
			
			//tratamentos
			if($v===''){
				$v='null';
			}else{
				if($this->tipos[$i]=='string'){
					$MitiBD->escapar($v);
					$v='"'.$v.'"';
				}else{
					settype($v,$this->tipos[$i]);
				}
			}
			
			$values[]=$v;
		}
		$sql.=implode(',',$values);
		
		$sql.=')';
		
		//requisicao
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
		
		return $MitiBD;
	}
	
	public function ler($filtros=array()){
		//banco
		$MitiBD=new MitiBD();
		
		//criacao do vetor
		$where=array();
		
		foreach($filtros as $i=>$v){
			//tratamentos
			if($v[0]=='like'||$this->tipos[$i]=='string'){$MitiBD->escapar($v[1]);}
			
			if($v[0]=='like'){
				$v[1]='"%'.$v[1].'%"';
			}else if($this->tipos[$i]=='string'){
				$v[1]='"'.$v[1].'"';
			}else{
				settype($v[1],$this->tipos[$i]);
			}
			
			//criacao do vetor
			$where[]=$this->ar->getTabela().'.'.$i.' '.$v[0].' '.$v[1];
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
			if($this->anulaveis[$i]==false&&$v==''){throw new Exception('Informe um valor');}
			if(strlen($v)>$this->tamanhos[$i]){throw new Exception('Limite de caractéres excedido');}
			
			//tratamentos
			if($v===''){
				$v='null';
			}else{
				if($this->tipos[$i]=='string'){
					$MitiBD->escapar($v);
					$v='"'.$v.'"';
				}else{
					settype($v,$this->tipos[$i]);
				}
			}
			
			$atribuicoes[]=$i.'='.$v;
		}
		
		$sql.=implode(',',$atribuicoes);
		
		if($this->ar->getPkTipo()=='string'){$pk='"'.$pk.'"';}
		$sql.=' where '.$this->ar->getPkCampo().'='.$pk;
		
		//requisicao
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
		
		return $MitiBD;
	}
	
	public function deletar($valor){
		//banco
		$MitiBD=new MitiBD();
		
		//tratamentos
		if($this->ar->getPkTipo()=='string'){
			$MitiBD->escapar($valor);
			$valor='"'.$valor.'"';
		}else{
			settype($v,$this->ar->getPkTipo());
		}
		
		//requisicao
		$sql='delete from '.$this->ar->getTabela().' where '.$this->ar->getPkCampo().'='.$valor;
		
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
		
		return $MitiBD;
	}
	
	public function definirCampos($ar_campos,$arx_campos=array()){
		//criacao da string da ar
		$campos_ar=array();
		foreach($ar_campos as $v){$campos_ar[]=$this->ar->getTabela().'.'.$v;}
		$campos_ar=implode(',',$campos_ar);
		
		//criacao da string das arx
		$campos_arx=array();
		
		foreach($this->arx as $i=>$o){
			foreach($arx_campos[$i] as $v){
				$campos_arx[]=$o->getTabela().'.'.$v.' as '.$o->getTabela().'_'.$v;
			}
		}
		
		$campos_arx=implode(',',$campos_arx);
		
		//ar + arx
		$campos=$campos_ar;
		if($campos_arx!=''){$campos.=','.$campos_arx;}
		
		//atribuicao
		$this->campos=$campos;
	}
	
	public function juntar($arx,$tabelas,$ar_chaves,$arx_chaves){
		//novos objetos
		foreach($arx as $o){
			$this->arx[]=$o;
		}
		
		//construcao da string
		$join='';
		
		foreach($this->arx as $i=>$o){
			$join.=' join '.$o->getTabela().
					' on '.$tabelas[$i].'.'.$ar_chaves[$i].
					'='.$o->getTabela().'.'.$arx_chaves[$i]
			;
		}
		
		$this->join=$join;
	}
	
	public function ordenar($duplas){
		//criacao do vetor
		$order_by=array();
		
		foreach($duplas as $i=>$v){
			$order_by[]=$this->ar->getTabela().'.'.$i.' '.$v;
		}
		
		//construcao da string
		$order_by=implode(',',$order_by);
		$order_by=' order by '.$order_by;
		
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
