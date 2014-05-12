<?php
class MitiORM2{
	private $alias;
	private $MitiTabela=array();
	private $MitiBD;
	private $campos='';
	private $juncoes='';
	private $filtros='';
	private $ordens='';
	
	public function __construct($tabela){
		$this->alias=substr($tabela,0,1);
		$this->MitiTabela[$this->alias]=new MitiTabela($tabela);
		
		$this->MitiBD=new MitiBD;
	}
	
	public function selecionar($alias,$campo,$alias_campo='',$separador=''){
		if($alias_campo){
			$alias_campo=' as '.$alias_campo;
		}
		
		$this->campos.=$separador.$alias.'.'.$campo.$alias_campo.' ';
		return $this;
	}
	
	public function eSelecionar($alias,$campo,$alias_campo=''){
		$this->selecionar($alias,$campo,$alias_campo,',');
		return $this;
	}
	
	public function juntar(
		$juncao,$externa,$alias,$alias_campo,$campo,$alias_campo_externa,$campo_externa
	){
		$this->MitiTabela[$alias]=new MitiTabela($externa);
		
		$this->juncoes.=
			$juncao.' '.$externa.' '.$alias
			.' on '.$alias_campo.'.'.$campo
			.'='.$alias_campo_externa.'.'.$campo_externa.' '
		;
		
		return $this;
	}
	
	public function filtrar($alias,$campo,$operador,$valor,$separador=''){
		$this->tratarLeitura($alias,$campo,$operador,$valor);
		$this->filtros.=$separador.' '.$alias.'.'.$campo.' '.$operador.' '.$valor.' ';
		return $this;
	}
	
	public function eFiltrar($alias,$campo,$operador,$valor){
		$this->filtrar($alias,$campo,$operador,$valor,'and');
		return $this;
	}
	
	public function ouFiltrar($alias,$campo,$operador,$valor){
		$this->filtrar($alias,$campo,$operador,$valor,'or');
		return $this;
	}
	
	private function tratarLeitura($alias,$campo,$operador,&$valor){
		$tipos=$this->MitiTabela[$alias]->getTipos();
		
		if($operador==='like'){
			$valor='"%'.$this->MitiBD->escapar($valor).'%"';
		}else if($tipos[$campo]==='string'){
			$valor='"'.$this->MitiBD->escapar($valor).'"';
		}else{
			settype($valor,$tipos[$campo]);
		}
	}
	
	public function ordenar($alias,$campo,$ordens,$separador=''){
		$this->ordens.=$separador.$alias.'.'.$campo.' '.$ordens.' ';
		return $this;
	}
	
	public function eOrdenar($alias,$campo,$ordens){
		$this->ordenar($alias,$campo,$ordens,',');
		return $this;
	}
	
	public function ler(){
		$this->verificarClausulas();
		
		//throw new Exception(
		return $this->MitiBD->requisitar(
			'select '
				.$this->campos
			.'from '.$this->MitiTabela[$this->alias]->getNome().' '.$this->alias.' '
			.$this->juncoes
			.$this->filtros
			.$this->ordens
		);
	}
	
	private function verificarClausulas(){
		if($this->filtros){
			$this->filtros='where '.$this->filtros;
		}
		
		if($this->ordens){
			$this->ordens='order by '.$this->ordens;
		}
	}
}
