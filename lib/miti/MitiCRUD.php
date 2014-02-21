<?php
class MitiCRUD{
	private $MitiBD;
	private $ar;
	private $tipos;
	private $anulaveis;
	private $tamanhos;
	private $arx=array();
	private $aliases;
	private $campos;
	private $join='';
	private $order_by='';
	private $limit='';
	
	public function __construct($ar){
		$this->MitiBD=new MitiBD();
	
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
	
	private function tratar(&$duplas){
		foreach($duplas as $i=>$v){
			if($v===''){
				$duplas[$i]='null';
			}else{
				if($this->tipos[$i]==='string'){
					$this->MitiBD->escapar($duplas[$i]);
					$duplas[$i]='"'.$v.'"';
				}else{
					settype($duplas[$i],$this->tipos[$i]);
				}
			}
		}
	}
	
	private function montarCampos(&$sql,$duplas){
		$sql='insert into '.$this->ar->getTabela().'(';
		
		$campos=array();
		foreach($duplas as $i=>$v){$campos[]=$i;}
		$sql.=implode(',',$campos);
		
		$sql.=')';
	}
	
	private function montarValores(&$sql,$duplas){
		$sql.='values(';
		$this->validar($duplas);
		$this->tratar($duplas);
		
		$values=array();
		foreach($duplas as $i=>$v){$values[]=$v;}
		$sql.=implode(',',$values);
		
		$sql.=')';
	}
	
	public function inserir($duplas){
		$sql='';
		$this->montarCampos($sql,$duplas);
		$this->montarValores($sql,$duplas);
		$this->MitiBD->requisitar($sql);
		return $this->MitiBD;
	}
	
	private function tratarLeitura(&$filtros,$tipos=array()){
		if(empty($tipos)){$tipos=$this->tipos;}
	
		foreach($filtros as $i=>$v){
			if($v[0]==='like'||$tipos[$i]==='string'){$this->MitiBD->escapar($filtros[$i][1]);}
			
			if($v[0]==='like'){
				$filtros[$i][1]='"%'.$v[1].'%"';
			}else if($tipos[$i]==='string'){
				$filtros[$i][1]='"'.$v[1].'"';
			}else{
				settype($filtros[$i][1],$tipos[$i]);
			}
		}
	}
	
	private function montarFiltros(&$where,$filtros){
		if(!empty($filtros)){
			$this->tratarLeitura($filtros);
			foreach($filtros as $i=>$v){$where[]=$this->ar->getTabela().'.'.$i.' '.$v[0].' '.$v[1];}
		}
	}
	
	private function montarARXFiltros(&$where,$arx_filtros){
		if(!empty($arx_filtros)){
			foreach($this->arx as $i=>$o){
				$tipos=$o->getTipos();
				$this->tratarLeitura($arx_filtros[$i],$tipos);
				foreach($arx_filtros[$i] as $j=>$v){$where[]=$o->getTabela().'.'.$j.' '.$v[0].' '.$v[1];}
			}
		}
	}
	
	private function montarWhere(&$where){
		if(!empty($where)){
			$where=implode(' and ',$where);
			$where=' where '.$where;
		}else{
			$where='';
		}
	}
	
	private function montarLeitura($where){
		return '
			select
				'.$this->campos.
			' from '.$this->ar->getTabela().
			$this->join.
			$where.
			$this->order_by.
			$this->limit
		;
	}
	
	public function ler($filtros=array(),$arx_filtros=array()){
		$where=array();
		$this->montarFiltros($where,$filtros);
		$this->montarARXFiltros($where,$arx_filtros);
		$this->montarWhere($where);
		$sql=$this->montarLeitura($where);
		$this->MitiBD->requisitar($sql);
		return $this->MitiBD;
	}
	
	private function tratarPk(&$pk){
		if($this->ar->getPkTipo()==='string'){
			$this->MitiBD->escapar($pk);
			$pk='"'.$pk.'"';
		}else{
			settype($pk,$this->ar->getPkTipo());
		}
	}
	
	private function montarAtribuicoes(&$sql,$duplas){
		$sql='update '.$this->ar->getTabela().' set ';
		
		$this->validar($duplas);
		$this->tratar($duplas);
		
		$atribuicoes=array();
		foreach($duplas as $i=>$v){$atribuicoes[]=$i.'='.$v;}
		$sql.=implode(',',$atribuicoes);
	}
	
	private function montarWhereAlteracao(&$sql,$pk){
		$this->tratarPk($pk);
		$sql.=' where '.$this->ar->getPkCampo().'='.$pk;
	}
	
	public function alterar($duplas,$pk){
		$sql='';
		$this->montarAtribuicoes($sql,$duplas);
		$this->montarWhereAlteracao($sql,$pk);
		$this->MitiBD->requisitar($sql);
		return $this->MitiBD;
	}
	
	private function montarExclusao($pk){
		$this->tratarPk($pk);
		return 'delete from '.$this->ar->getTabela().' where '.$this->ar->getPkCampo().'='.$pk;
	}
	
	public function deletar($pk){
		$sql=$this->montarExclusao($pk);
		$this->MitiBD->requisitar($sql);
		return $this->MitiBD;
	}
	
	private function montarARCampos(&$ar_campos){
		$campos=array();
		foreach($ar_campos as $v){$campos[]=$this->ar->getTabela().'.'.$v;}
		$ar_campos=implode(',',$campos);
	}
	
	private function montarARXCampos(&$arx_campos){
		$campos=array();
		
		if(!empty($arx_campos)){
			foreach($this->aliases as $i=>$v){
				foreach($arx_campos[$i] as $x){
					$campos[]=$v.'.'.$x.' as '.$v.'_'.$x;
				}
			}
		}
		
		$arx_campos=implode(',',$campos);
	}
	
	public function definirCampos($ar_campos,$arx_campos=array()){
		$this->montarARCampos($ar_campos);
		$this->montarARXCampos($arx_campos);
		
		$campos=$ar_campos;
		if($arx_campos){$campos.=','.$arx_campos;}
		
		$this->campos=$campos;
	}
	
	public function juntar($joins,$arx,$aliases,$tabelas,$ar_chaves,$arx_chaves){
		$this->arx=$arx;
		$this->aliases=$aliases;
		
		$join='';
		foreach($arx as $i=>$o){
			$join.=' '.$joins[$i].' '.$o->getTabela().' '.$aliases[$i].
					' on '.$tabelas[$i].'.'.$ar_chaves[$i].
					'='.$aliases[$i].'.'.$arx_chaves[$i]
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