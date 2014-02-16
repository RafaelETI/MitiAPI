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
	
	private function validar($duplas){
		foreach($duplas as $i=>$v){
			if(!$this->anulaveis[$i]&&!$v){throw new Exception('Valor vazio');}
			if(strlen($v)>$this->tamanhos[$i]){throw new Exception('Limite de caractéres excedido');}
		}
	}
	
	private function tratar(&$duplas,$MitiBD){
		foreach($duplas as $i=>$v){
			if($v===''){
				$duplas[$i]='null';
			}else{
				if($this->tipos[$i]==='string'){
					$MitiBD->escapar($duplas[$i]);
					$duplas[$i]='"'.$v.'"';
				}else{
					settype($duplas[$i],$this->tipos[$i]);
				}
			}
		}
	}
	
	public function inserir($duplas){
		$MitiBD=new MitiBD();
		
		$sql='insert into '.$this->ar->getTabela().'(';
		
		$campos=array();
		foreach($duplas as $i=>$v){$campos[]=$i;}
		$sql.=implode(',',$campos);
		
		$sql.=')values(';
		
		$this->validar($duplas);
		$this->tratar($duplas,$MitiBD);
		$values=array();
		foreach($duplas as $i=>$v){$values[]=$v;}
		$sql.=implode(',',$values);
		
		$sql.=')';
		
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
		
		return $MitiBD;
	}
	
	private function tratarLeitura(&$filtros,$MitiBD,$tipos=array()){
		if(count($tipos)===0){$tipos=$this->tipos;}
	
		foreach($filtros as $i=>$v){
			if($v[0]==='like'||$tipos[$i]==='string'){$MitiBD->escapar($filtros[$i][1]);}
			
			if($v[0]==='like'){
				$filtros[$i][1]='"%'.$v[1].'%"';
			}else if($tipos[$i]==='string'){
				$filtros[$i][1]='"'.$v[1].'"';
			}else{
				settype($filtros[$i][1],$tipos[$i]);
			}
		}
	}
	
	public function ler($filtros=array(),$arx_filtros=array()){
		$MitiBD=new MitiBD();
		
		$where=array();
		
		if(count($filtros)>0){
			$this->tratarLeitura($filtros,$MitiBD);
			foreach($filtros as $i=>$v){$where[]=$this->ar->getTabela().'.'.$i.' '.$v[0].' '.$v[1];}
		}
		
		if(count($arx_filtros)>0){
			foreach($this->arx as $i=>$o){
				$tipos=$o->getTipos();
				$this->tratarLeitura($arx_filtros[$i],$MitiBD,$tipos);
				foreach($arx_filtros[$i] as $j=>$v){$where[]=$o->getTabela().'.'.$j.' '.$v[0].' '.$v[1];}
			}
		}
		
		if(count($where)>0){
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
		
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
		
		return $MitiBD;
	}
	
	private function tratarPk(&$pk,$MitiBD){
		if($this->ar->getPkTipo()==='string'){
			$MitiBD->escapar($pk);
			$pk='"'.$pk.'"';
		}else{
			settype($pk,$this->ar->getPkTipo());
		}
	}
	
	public function alterar($duplas,$pk){
		$MitiBD=new MitiBD();
		
		$sql='update '.$this->ar->getTabela().' set ';
		
		$this->validar($duplas);
		$this->tratar($duplas,$MitiBD);
		$atribuicoes=array();
		foreach($duplas as $i=>$v){$atribuicoes[]=$i.'='.$v;}
		$sql.=implode(',',$atribuicoes);
		
		$this->tratarPk($pk,$MitiBD);
		$sql.=' where '.$this->ar->getPkCampo().'='.$pk;
		
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
		
		return $MitiBD;
	}
	
	public function deletar($pk){
		$MitiBD=new MitiBD();
		
		$this->tratarPk($pk,$MitiBD);
		$sql='delete from '.$this->ar->getTabela().' where '.$this->ar->getPkCampo().'='.$pk;
		
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
		
		return $MitiBD;
	}
	
	public function definirCampos($ar_campos,$arx_campos=array()){
		$campos_ar=array();
		foreach($ar_campos as $v){$campos_ar[]=$this->ar->getTabela().'.'.$v;}
		$campos_ar=implode(',',$campos_ar);
		
		$campos_arx=array();
		if(count($arx_campos)>0){
			foreach($this->arx as $i=>$o){
				foreach($arx_campos[$i] as $v){
					$campos_arx[]=$o->getTabela().'.'.$v.' as '.$o->getTabela().'_'.$v;
				}
			}
		}
		
		$campos_arx=implode(',',$campos_arx);
		
		$campos=$campos_ar;
		if($campos_arx){$campos.=','.$campos_arx;}
		
		$this->campos=$campos;
	}
	
	public function juntar($joins,$arx,$tabelas,$ar_chaves,$arx_chaves){
		foreach($arx as $o){$this->arx[]=$o;}
		
		$join='';
		foreach($this->arx as $i=>$o){
			$join.=' '.$joins[$i].' '.$o->getTabela().
					' on '.$tabelas[$i].'.'.$ar_chaves[$i].
					'='.$o->getTabela().'.'.$arx_chaves[$i]
			;
		}
		
		$this->join=$join;
	}
	
	public function ordenar($duplas){
		$order_by=array();
		foreach($duplas as $i=>$v){$order_by[]=$this->ar->getTabela().'.'.$i.' '.$v;}
		
		$order_by=implode(',',$order_by);
		$order_by=' order by '.$order_by;
		
		$this->order_by=$order_by;
	}
	
	public function limitar($casas,$inicio=''){
		if($inicio){$inicio.=',';}
		$limit=' limit '.$inicio.$casas;
		
		$this->limit=$limit;
	}
}
?>
