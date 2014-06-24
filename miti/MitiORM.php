<?php
/**
 * MitiAPI, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * ORM (Object Relational Mapping)
 * 
 * H� uma depend�ncia com a classe MitiTabela que � a que na verdade faz o
 * mapeamento com a tabela do banco. Essa classe � respons�vel por realizar os
 * manuseios no banco baseando-se nesse mapeamento.
 * 
 * A caracter�stica mais vis�vel para o usu�rio, ao utilizar esse classe, � a
 * elimina��o da necessidade de se escrever c�digos SQL. Al�m desta, existem
 * outras vantagens como a elimina��o da necessidade de se preocupar com
 * abertura e fechamento de conex�o com o banco, com tratamento contra SQL
 * Injection, assim como para cadastro de valores null, com valida��es de
 * tamanho e tipo de dados, etc.
 * 
 * Uma regra importante de se ter em mente ao realizar requisi��es select por
 * essa classe � a seguinte: existem duas perspectivas de tabelas do banco: a
 * tabela principal, e as externas. A principal � a que a requisi��o � direcionada,
 * ou seja, a que � designada ap�s a cl�usula from do SQL; as externas s�o as
 * que s�o juntadas � principal atrav�s de cl�usulas join.
 */
class MitiORM{
	/**
	 * @var string Alias da tabela principal da requisi��o.
	 */
	private $alias;
	
	/**
	 * @var \MitiTabela[] Indexado pelo alias de cada tabela. O objeto da tabela
	 * principal fica na primeira posi��o, e as externas no resto.
	 */
	private $MitiTabela=array();
	
	/**
	 * @var \MitiBD
	 */
	private $MitiBD;
	
	/**
	 * @var string Concatena��o dos campos � serem selecionados no select.
	 */
	private $campos='';
	
	/**
	 * @var string Concatena��o dos joins do select.
	 */
	private $juncoes='';
	
	/**
	 * @var string Concatena��o dos filtros para a cl�usula where.
	 */
	private $filtros='';
	
	/**
	 * @var string Concatena��o dos agrupamentos para a cl�usula group by.
	 */
	private $grupos='';
	
	/**
	 * @var string Concatena��o das ordena��es para a cl�usula order by.
	 */
	private $ordens='';
	
	/**
	 * @var string Concatena��o do limite de registros no select, possivelmente
	 * com a defini��o de um in�cio.
	 */
	private $limite;
	
	/**
	 * Define o alias e objeto da tabela principal, e a conex�o com o banco
	 * 
	 * O alias da principal � sempre a primeira letra no seu nome.
	 * 
	 * Os aliases das externas s�o preferencialmente tamb�m as primeiras letras,
	 * mas em caso de conflito, pode-se usar mais letras. Eles s�o definidos nas
	 * jun��es, assim como os objetos de mapeamento respectivos.
	 * 
	 * @api
	 * @param string $tabela Nome da tabela principal.
	 */
	public function __construct($tabela){
		$this->alias=substr($tabela,0,1);
		$this->MitiTabela[$this->alias]=new MitiTabela($tabela);
		
		$this->MitiBD=new MitiBD;
	}
	
	/**
	 * Cria um registro na tabela (Create do CRUD)
	 * 
	 * Uma das principais informa��es conseguidas atrav�s do retorno � o valor
	 * do id auto incrementado gerado pelo banco para o registro que acabara de
	 * ser inserido.
	 * 
	 * A forma mais pr�tica de se criar o vetor � ser criado no banco � dar
	 * aos names dos campos do formul�rio, os mesmos nomes do campos da tabela.
	 * Pode-se argumentar que � uma falha de seguran�a, mas pode valer a pena.
	 * 
	 * @api
	 * @param string[] $duplas Vetor indexado pelos nomes dos campos da tabela.
	 * @return \MitiBD
	 * @throws Exception Implicitamente.
	 */
	public function criar(array $duplas){
		$sql='';
		$sql=$this->montarCampos($sql,$duplas);
		$sql=$this->montarValores($sql,$duplas);
		return $this->MitiBD->requisitar($sql);
	}
	
	/**
	 * Monta o in�cio e a parte dos campos da instru��o
	 * 
	 * @param string $sql
	 * @param string[] $duplas
	 * @return string
	 */
	private function montarCampos($sql,array $duplas){
		$sql='insert into '.$this->MitiTabela[$this->alias]->getNome().'(';
		
		$campos=array();
		foreach($duplas as $i=>$v){
			$campos[]=$i;
		}
		
		$sql.=implode(',',$campos);
		$sql.=')';
		
		return $sql;
	}
	
	/**
	 * Monta a parte dos valores e o final da instru��o
	 * 
	 * @param string $sql
	 * @param string[] $duplas
	 * @return string
	 */
	private function montarValores($sql,array $duplas){
		$this->validar($duplas);
		$duplas=$this->tratar($duplas);
		
		$sql.='values(';
		
		$values=array();
		foreach($duplas as $v){
			$values[]=$v;
		}
		
		$sql.=implode(',',$values);
		$sql.=')';
		
		return $sql;
	}
	
	/**
	 * Atualiza um registro na tabela (Update do CRUD)
	 * 
	 * A forma mais pr�tica de se criar o vetor � ser criado no banco � dar
	 * aos names dos campos do formul�rio, os mesmos nomes do campos da tabela.
	 * Pode-se argumentar que � uma falha de seguran�a, mas pode valer a pena.
	 * 
	 * @api
	 * @param string[] $duplas Vetor indexado pelos nomes dos campos da tabela.
	 * @param string $pk Nome do campo da chave prim�ria.
	 * @return \MitiBD
	 * @throws Exception Implicitamente.
	 */
	public function atualizar(array $duplas,$pk){
		$sql='';
		$sql=$this->montarAtribuicoes($sql,$duplas);
		$sql=$this->montarWhereAlteracao($sql,$pk);
		return $this->MitiBD->requisitar($sql);
	}
	
	/**
	 * Monta a parte das atribui��es de valores da instru��o
	 * 
	 * @param string $sql
	 * @param string[] $duplas
	 * @return string
	 */
	private function montarAtribuicoes($sql,array $duplas){
		$this->validar($duplas);
		$duplas=$this->tratar($duplas);
		
		$sql='update '.$this->MitiTabela[$this->alias]->getNome().' set ';
		
		$atribuicoes=array();
		foreach($duplas as $i=>$v){
			$atribuicoes[]=$i.'='.$v;
		}
		
		$sql.=implode(',',$atribuicoes);
		
		return $sql;
	}
	
	/**
	 * Monta a parte do filtro da instru��o
	 * 
	 * Atualmente apenas pode-se filtrar pela chave prim�ria, mas pretende-se
	 * poder filtrar por qualquer campo, assim como com o m�todo de exclus�o.
	 * 
	 * @param string $sql
	 * @param string $pk
	 * @return string
	 */
	private function montarWhereAlteracao($sql,$pk){
		$pk=$this->tratarPk($pk);
		return $sql.' where '.$this->MitiTabela[$this->alias]->getPkCampo().'='.$pk;
	}
	
	/**
	 * Valida os dados � serem inseridos
	 * 
	 * @param string[] $duplas
	 * 
	 * @throws Exception Se o valor for vazio e o campo n�o permitir nulo, ou se
	 * o valor exceder o limite de caract�res que o campo permite.
	 */
	private function validar(array $duplas){
		$tamanhos=$this->MitiTabela[$this->alias]->getTamanhos();
		$anulaveis=$this->MitiTabela[$this->alias]->getAnulaveis();
		
		foreach($duplas as $i=>$v){
			if(!$anulaveis[$i]&&!$v){
				throw new Exception('Valor vazio.');
			}
			
			if(strlen($v)>$tamanhos[$i]){
				throw new Exception('Limite de caract�res excedido.');
			}
		}
	}
	
	/**
	 * Exclui um registro na tabela (Delete do CRUD)
	 * 
	 * @api
	 * @param mixed|mixed[] $filtro Se for um vetor, deve conter apenas uma dupla.
	 * @return \MitiBD
	 * @throws Exception Implicitamente.
	 */
	public function deletar($filtro){
		if(is_array($filtro)){
			$sql=$this->montarExclusaoArray($filtro);
		}else{
			$sql=$this->montarExclusaoScalar($filtro);
		}
		
		return $this->MitiBD->requisitar($sql);
	}
	
	/**
	 * Monta a instru��o com um vetor
	 * 
	 * @param mixed[] $dupla
	 * @return string
	 */
	private function montarExclusaoArray(array $dupla){
		$dupla=$this->tratar($dupla);
		
		foreach($dupla as $i=>$v){
			$sql=
				'delete from '.$this->MitiTabela[$this->alias]->getNome()
				.' where '.$i.'='.$v
			;
		}
		
		return $sql;
	}
	
	/**
	 * Monta a instru��o com um valor
	 * 
	 * @param mixed $pk
	 * @return string
	 */
	private function montarExclusaoScalar($pk){
		$pk=$this->tratarPk($pk);
		
		return
			'delete from '.$this->MitiTabela[$this->alias]->getNome()
			.' where '.$this->MitiTabela[$this->alias]->getPkCampo().'='.$pk
		;
	}
	
	/**
	 * Trata os dados
	 * 
	 * Impede-se SQL Injection, al�m de outros tratamentos.
	 * 
	 * @param string[] $duplas
	 * @return string[]
	 */
	private function tratar(array $duplas){
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
		
		return $duplas;
	}
	
	/**
	 * Trata o dado referente � chave prim�ria
	 * 
	 * Impede-se SQL Injection.
	 * 
	 * @param string $pk
	 * @return string
	 */
	private function tratarPk($pk){
		if($this->MitiTabela[$this->alias]->getPkTipo()==='string'){
			$pk=$this->MitiBD->escapar($pk);
			$pk='"'.$pk.'"';
		}else{
			settype($pk,$this->MitiTabela[$this->alias]->getPkTipo());
		}
		
		return $pk;
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
		$valor=$this->tratarLeitura($alias,$campo,$operador,$valor);
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
	
	private function tratarLeitura($alias,$campo,$operador,$valor){
		$tipos=$this->MitiTabela[$alias]->getTipos();
		
		if($operador==='like'){
			$valor='"%'.$this->MitiBD->escapar($valor).'%"';
		}else if($tipos[$campo]==='string'){
			$valor='"'.$this->MitiBD->escapar($valor).'"';
		}else{
			settype($valor,$tipos[$campo]);
		}
		
		return $valor;
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
