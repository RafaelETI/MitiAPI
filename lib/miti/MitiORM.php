<?php
class MitiORM{
	private $MitiBD;
	private $MitiTabela;
	private $tipos;
	private $anulaveis;
	private $tamanhos;
	private $MitiTabelas=array();
	private $joins;
	private $aliases;
	private $Ontabelas;
	private $tabela_chaves;
	private $tabelas_chaves;
	private $campos;
	private $join='';
	private $group_by='';
	private $order_by='';
	private $limit='';
	
	public function __construct($tabela){
		$this->MitiBD=MitiBD::getInstance();
	
		$this->MitiTabela=new MitiTabela($tabela);
		$this->tipos=$this->MitiTabela->getTipos();
		$this->anulaveis=$this->MitiTabela->getAnulaveis();
		$this->tamanhos=$this->MitiTabela->getTamanhos();
	}
	
	public function criar(array $duplas){
		$sql='';
		$this->montarCampos($sql,$duplas);
		$this->montarValores($sql,$duplas);
		return $this->MitiBD->requisitar($sql);
	}
	
	private function montarCampos(&$sql,array &$duplas){
		$sql='insert into '.$this->MitiTabela->getNome().'(';
		
		$campos=array();
		foreach($duplas as $i=>$v){
			$campos[]=$i;
		}
		
		$sql.=implode(',',$campos);
		$sql.=')';
	}
	
	private function montarValores(&$sql,array $duplas){
		$this->validar($duplas);
		$this->tratar($duplas);
		
		$sql.='values(';
		
		$values=array();
		foreach($duplas as $v){
			$values[]=$v;
		}
		
		$sql.=implode(',',$values);
		$sql.=')';
	}
	
	public function setJoins(array $joins){
		$this->joins=$joins;
		return $this;
	}
	
	public function setAliases(array $aliases){
		$this->aliases=$aliases;
		return $this;
	}
	
	public function setOnTabelas(array $Ontabelas){
		$this->Ontabelas=$Ontabelas;
		return $this;
	}
	
	public function setTabelaChaves(array $tabela_chaves){
		$this->tabela_chaves=$tabela_chaves;
		return $this;
	}
	
	public function setTabelasChaves(array $tabelas_chaves){
		$this->tabelas_chaves=$tabelas_chaves;
		return $this;
	}
	
	public function juntar(array $tabelas){
		foreach($tabelas as $v){
			$this->MitiTabelas[]=new MitiTabela($v);
		}
		
		$join='';
		foreach($this->MitiTabelas as $i=>$o){
			$join.=' '.$this->joins[$i].' '.$o->getNome().' '.$this->aliases[$i].
					' on '.$this->Ontabelas[$i].'.'.$this->tabela_chaves[$i].
					'='.$this->aliases[$i].'.'.$this->tabelas_chaves[$i]
			;
		}
		
		$this->join=$join;
		return $this;
	}
	
	public function definirCampos(array $tabela_campos,array $tabelas_campos=array()){
		$this->montarTabelaCampos($tabela_campos);
		$campos=$tabela_campos;
		
		if($tabelas_campos){
			$this->montarTabelasCampos($tabelas_campos);
			$campos.=','.$tabelas_campos;
		}
		
		$this->campos=$campos;
		return $this;
	}
	
	private function montarTabelaCampos(array &$tabela_campos){
		$campos=array();
		foreach($tabela_campos as $v){
			$campos[]=$this->MitiTabela->getNome().'.'.$v;
		}
		
		$tabela_campos=implode(',',$campos);
	}
	
	private function montarTabelasCampos(array &$tabelas_campos){
		$campos=array();
		
		foreach($this->aliases as $i=>$v){
			if(!isset($tabelas_campos[$i])){
				continue;
			}
			
			foreach($tabelas_campos[$i] as $x){
				$campos[]=$v.'.'.$x.' as '.$v.'_'.$x;
			}
		}
		
		$tabelas_campos=implode(',',$campos);
	}
	
	public function agrupar(array $grupos,array $tabelas_grupos=array()){
		$group_by=array();
		
		$this->montarTabelaGrupos($group_by,$grupos);
		$this->montarTabelasGrupos($group_by,$tabelas_grupos);
		
		$group_by=implode(',',$group_by);
		$group_by=' group by '.$group_by;
		
		$this->group_by=$group_by;
		return $this;
	}
	
	private function montarTabelaGrupos(array &$group_by,array $grupos){
		foreach($grupos as $v){
			$group_by[]=$this->MitiTabela->getNome().'.'.$v;
		}
	}
	
	private function montarTabelasGrupos(array &$group_by,array $tabelas_grupos){
		foreach($this->MitiTabelas as $i=>$o){
			if(!isset($tabelas_grupos[$i])){
				continue;
			}
			
			foreach($tabelas_grupos[$i] as $v){
				$group_by[]=$o->getNome().'.'.$v;
			}
		}
	}
	
	public function ordenar(array $ordens,array $tabelas_ordens=array()){
		$order_by=array();
		
		$this->montarTabelaOrdens($order_by,$ordens);
		$this->montarTabelasOrdens($order_by,$tabelas_ordens);
		
		$order_by=implode(',',$order_by);
		$order_by=' order by '.$order_by;
		
		$this->order_by=$order_by;
		return $this;
	}
	
	private function montarTabelaOrdens(array &$order_by,array $ordens){
		foreach($ordens as $i=>$v){
			$order_by[]=$this->MitiTabela->getNome().'.'.$i.' '.$v;
		}
	}
	
	private function montarTabelasOrdens(array &$order_by,array $tabelas_ordens){
		foreach($this->MitiTabelas as $i=>$o){
			if(!isset($tabelas_ordens[$i])){
				continue;
			}
			
			foreach($tabelas_ordens[$i] as $j=>$v){
				$order_by[]=$o->getNome().'.'.$j.' '.$v;
			}
		}
	}
	
	public function ordenarAleatoriamente(){
		$this->order_by=' order by rand()';
		return $this;
	}
	
	public function limitar($casas,$inicio=''){
		if($inicio){
			$inicio.=',';
		}
		
		$limit=' limit '.$inicio.$casas;
		
		$this->limit=$limit;
		return $this;
	}
	
	public function ler(array $filtros=array(),array $tabelas_filtros=array()){
		$where=array();
		$this->montarFiltros($where,$filtros);
		$this->montarTabelasFiltros($where,$tabelas_filtros);
		$this->montarWhere($where);
		$sql=$this->montarLeitura($where);
		
		return $this->MitiBD->requisitar($sql);
	}
	
	private function montarFiltros(array &$where,array $filtros){
		if($filtros){
			$this->tratarLeitura($filtros);
			
			foreach($filtros as $i=>$v){
				$where[]=$this->MitiTabela->getNome().'.'.$i.' '.$v[0].' '.$v[1];
			}
		}
	}
	
	private function montarTabelasFiltros(array &$where,array $tabelas_filtros){
		if($tabelas_filtros){
			foreach($this->MitiTabelas as $i=>$o){
				if(!isset($tabelas_filtros[$i])){
					continue;
				}
				
				$tipos=$o->getTipos();
				$this->tratarLeitura($tabelas_filtros[$i],$tipos);
				
				foreach($tabelas_filtros[$i] as $j=>$v){
					$where[]=$this->aliases[$i].'.'.$j.' '.$v[0].' '.$v[1];
				}
			}
		}
	}
	
	private function tratarLeitura(array &$filtros,array $tipos=array()){
		if(!$tipos){
			$tipos=$this->tipos;
		}
	
		foreach($filtros as $i=>$v){
			if($v[0]==='like'||$tipos[$i]==='string'){
				$filtros[$i][1]=$this->MitiBD->escapar($v[1]);
			}
			
			if($v[0]==='like'){
				$filtros[$i][1]='"%'.$filtros[$i][1].'%"';
			}else if($tipos[$i]==='string'){
				$filtros[$i][1]='"'.$filtros[$i][1].'"';
			}else{
				settype($filtros[$i][1],$tipos[$i]);
			}
		}
	}
	
	private function montarWhere(array &$where){
		if($where){
			$where=implode(' and ',$where);
			$where=' where '.$where;
		}else{
			$where='';
		}
	}
	
	private function montarLeitura($where){
		return
			'select '.
				$this->campos.
			' from '.$this->MitiTabela->getNome().
			$this->join.
			$where.
			$this->group_by.
			$this->order_by.
			$this->limit
		;
	}
	
	public function atualizar(array $duplas,$pk){
		$sql='';
		$this->montarAtribuicoes($sql,$duplas);
		$this->montarWhereAlteracao($sql,$pk);
		return $this->MitiBD->requisitar($sql);
	}
	
	private function montarAtribuicoes(&$sql,array $duplas){
		$this->validar($duplas);
		$this->tratar($duplas);
		
		$sql='update '.$this->MitiTabela->getNome().' set ';
		
		$atribuicoes=array();
		foreach($duplas as $i=>$v){
			$atribuicoes[]=$i.'='.$v;
		}
		
		$sql.=implode(',',$atribuicoes);
	}
	
	private function validar(array $duplas){
		foreach($duplas as $i=>$v){
			if(!$this->anulaveis[$i]&&!$v){
				throw new Exception('Valor vazio');
			}
			
			if(strlen($v)>$this->tamanhos[$i]){
				throw new Exception('Limite de caractéres excedido');
			}
		}
	}
	
	private function montarWhereAlteracao(&$sql,$pk){
		$this->tratarPk($pk);
		$sql.=' where '.$this->MitiTabela->getPkCampo().'='.$pk;
	}
	
	public function deletar($filtro){
		if(is_array($filtro)){
			$sql=$this->montarExclusaoArray($filtro);
		}else{
			$sql=$this->montarExclusaoScalar($filtro);
		}
		
		return $this->MitiBD->requisitar($sql);
	}
	
	private function montarExclusaoArray($dupla){
		$this->tratar($dupla);
		
		foreach($dupla as $i=>$v){
			$sql='delete from '.$this->MitiTabela->getNome().' where '.$i.'='.$v;
		}
		
		return $sql;
	}
	
	private function tratar(array &$duplas){
		foreach($duplas as $i=>$v){
			if($v===''){
				$duplas[$i]='null';
			}else{
				if($this->tipos[$i]==='string'){
					$duplas[$i]=$this->MitiBD->escapar($v);
					$duplas[$i]='"'.$duplas[$i].'"';
				}else{
					settype($duplas[$i],$this->tipos[$i]);
				}
			}
		}
	}
	
	private function montarExclusaoScalar($pk){
		$this->tratarPk($pk);
		
		return 'delete from '.$this->MitiTabela->getNome().
			' where '.$this->MitiTabela->getPkCampo().'='.$pk;
	}
	
	private function tratarPk(&$pk){
		if($this->MitiTabela->getPkTipo()==='string'){
			$pk=$this->MitiBD->escapar($pk);
			$pk='"'.$pk.'"';
		}else{
			settype($pk,$this->MitiTabela->getPkTipo());
		}
	}
}