<?php
/**
 * MitiAPI, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
class MitiORM{
	private $alias;
	private $MitiTabela=array();
	private $MitiBD;
	private $campos='';
	private $juncoes='';
	private $filtros='';
	private $grupos='';
	private $ordens='';
	private $limite;
	
	public function __construct($tabela){
		$this->alias=substr($tabela,0,1);
		$this->MitiTabela[$this->alias]=new MitiTabela($tabela);
		
		$this->MitiBD=new MitiBD;
	}
	
	public function criar(array $duplas){
		$sql='';
		$this->montarCampos($sql,$duplas);
		$this->montarValores($sql,$duplas);
		return $this->MitiBD->requisitar($sql);
	}
	
	private function montarCampos(&$sql,array &$duplas){
		$sql='insert into '.$this->MitiTabela[$this->alias]->getNome().'(';
		
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
	
	public function atualizar(array $duplas,$pk){
		$sql='';
		$this->montarAtribuicoes($sql,$duplas);
		$this->montarWhereAlteracao($sql,$pk);
		return $this->MitiBD->requisitar($sql);
	}
	
	private function montarAtribuicoes(&$sql,array $duplas){
		$this->validar($duplas);
		$this->tratar($duplas);
		
		$sql='update '.$this->MitiTabela[$this->alias]->getNome().' set ';
		
		$atribuicoes=array();
		foreach($duplas as $i=>$v){
			$atribuicoes[]=$i.'='.$v;
		}
		
		$sql.=implode(',',$atribuicoes);
	}
	
	private function validar(array $duplas){
		$tamanhos=$this->MitiTabela[$this->alias]->getTamanhos();
		$anulaveis=$this->MitiTabela[$this->alias]->getAnulaveis();
		
		foreach($duplas as $i=>$v){
			if(!$anulaveis[$i]&&!$v){
				throw new Exception('Valor vazio');
			}
			
			if(strlen($v)>$tamanhos[$i]){
				throw new Exception('Limite de caractéres excedido');
			}
		}
	}
	
	private function montarWhereAlteracao(&$sql,$pk){
		$this->tratarPk($pk);
		$sql.=' where '.$this->MitiTabela[$this->alias]->getPkCampo().'='.$pk;
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
			$sql='delete from '.$this->MitiTabela[$this->alias]->getNome().' where '.$i.'='.$v;
		}
		
		return $sql;
	}
	
	private function tratar(array &$duplas){
		$tipos=$this->MitiTabela[$this->alias]->getTipos();
		
		foreach($duplas as $i=>$v){
			if($v===''){
				$duplas[$i]='null';
			}else{
				if($tipos[$i]==='string'){
					$duplas[$i]=$this->MitiBD->escapar($v);
					$duplas[$i]='"'.$duplas[$i].'"';
				}else{
					settype($duplas[$i],$tipos[$i]);
				}
			}
		}
	}
	
	private function montarExclusaoScalar($pk){
		$this->tratarPk($pk);
		
		return
			'delete from '.$this->MitiTabela[$this->alias]->getNome()
			.' where '.$this->MitiTabela[$this->alias]->getPkCampo().'='.$pk
		;
	}
	
	private function tratarPk(&$pk){
		if($this->MitiTabela[$this->alias]->getPkTipo()==='string'){
			$pk=$this->MitiBD->escapar($pk);
			$pk='"'.$pk.'"';
		}else{
			settype($pk,$this->MitiTabela[$this->alias]->getPkTipo());
		}
	}
	
	public function selecionar($alias,$campo,$alias_campo=''){
		if($alias){
			$alias.='.';
		}
		
		if($alias_campo){
			$alias_campo=' as '.$alias_campo;
		}
		
		$separador='';
		if($this->campos){
			$separador=',';
		}
		
		$this->campos.=$separador.$alias.$campo.$alias_campo.' ';
		return $this;
	}
	
	public function juntar($juncao,$externa,$alias,$alias_campo,$campo,$alias_campo_externa,$campo_externa){
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
	
	public function agrupar($alias,$campo){
		$separador='';
		if($this->grupos){
			$separador=',';
		}
		
		$this->grupos.=$separador.$alias.'.'.$campo.' ';
		return $this;
	}
	
	public function ordenar($alias,$campo,$ordens){
		$separador='';
		if($this->ordens){
			$separador=',';
		}
		
		$this->ordens.=$separador.$alias.'.'.$campo.' '.$ordens.' ';
		return $this;
	}
	
	public function ordenarAleatoriamente(){
		$this->ordens='rand()';
		return $this;
	}
	
	public function limitar($quantidade,$inicio=''){
		if(!$quantidade){
			return $this;
		}
		
		if($inicio!==''){
			$inicio.=',';
		}
		
		$this->limite=$inicio.$quantidade;
		return $this;
	}
	
	public function ler(){
		$this->verificarClausulas();
		
		$sql=
			'select '.$this->campos
			.'from '.$this->MitiTabela[$this->alias]->getNome().' '.$this->alias.' '
			.$this->juncoes
			.$this->filtros
			.$this->grupos
			.$this->ordens
			.$this->limite
		;
			
		return $this->MitiBD->requisitar($sql);
	}
	
	private function verificarClausulas(){
		if($this->filtros&&strpos($this->filtros,'where')===false){
			$this->filtros='where '.$this->filtros;
		}
		
		if($this->grupos&&strpos($this->grupos,'group by')===false){
			$this->grupos='group by '.$this->grupos;
		}
		
		if($this->ordens&&strpos($this->ordens,'order by')===false){
			$this->ordens='order by '.$this->ordens;
		}
		
		if($this->limite&&strpos($this->limite,'limit')===false){
			$this->limite='limit '.$this->limite;
		}
	}
}
