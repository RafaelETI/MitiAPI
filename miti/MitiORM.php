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
 * Há uma dependência com a classe MitiTabela que é a que na verdade faz o
 * mapeamento com a tabela do banco. Essa classe é responsável por realizar os
 * manuseios no banco baseando-se nesse mapeamento.
 * 
 * A característica mais visível para o usuário, ao utilizar esse classe, é a
 * eliminação da necessidade de se escrever códigos SQL. Além desta, existem
 * outras vantagens como a eliminação da necessidade de se preocupar com
 * abertura e fechamento de conexão com o banco, com tratamento contra SQL
 * Injection, assim como para cadastro de valores null, com validações de
 * tamanho e tipo de dados, etc.
 * 
 * Uma regra importante de se ter em mente ao realizar requisições select por
 * essa classe é a seguinte: existem duas perspectivas de tabelas do banco: a
 * tabela principal, e as externas. A principal é a que a requisição é direcionada,
 * ou seja, a que é designada após a cláusula from do SQL; as externas são as
 * que são juntadas à principal através de cláusulas join.
 */
class MitiORM{
	/**
	 * @var string Alias da tabela principal da requisição.
	 */
	private $alias;
	
	/**
	 * @var \MitiTabela[] Indexado pelo alias de cada tabela. O objeto da tabela
	 * principal fica na primeira posição, e as externas no resto.
	 */
	private $MitiTabela=array();
	
	/**
	 * @var \MitiBD
	 */
	private $MitiBD;
	
	/**
	 * @var string Concatenação dos campos à serem selecionados no select.
	 */
	private $campos='';
	
	/**
	 * @var string Concatenação dos joins do select.
	 */
	private $juncoes='';
	
	/**
	 * @var string Concatenação dos filtros para a cláusula where.
	 */
	private $filtros='';
	
	/**
	 * @var string Concatenação dos agrupamentos para a cláusula group by.
	 */
	private $grupos='';
	
	/**
	 * @var string Concatenação das ordenações para a cláusula order by.
	 */
	private $ordens='';
	
	/**
	 * @var string Concatenação do limite de registros no select, possivelmente
	 * com a definição de um início.
	 */
	private $limite;
	
	/**
	 * Define o alias e objeto da tabela principal, e a conexão com o banco
	 * 
	 * O alias da principal é sempre a primeira letra no seu nome.
	 * 
	 * Os aliases das externas são preferencialmente também as primeiras letras,
	 * mas em caso de conflito, pode-se usar mais letras. Eles são definidos nas
	 * junções, assim como os objetos de mapeamento respectivos.
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
	 * Uma das principais informações conseguidas através do retorno é o valor
	 * do id auto incrementado gerado pelo banco para o registro que acabara de
	 * ser inserido.
	 * 
	 * A forma mais prática de se criar o vetor à ser criado no banco é dar
	 * aos names dos campos do formulário, os mesmos nomes do campos da tabela.
	 * Pode-se argumentar que é uma falha de segurança, mas pode valer a pena.
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
	 * Monta o início e a parte dos campos da instrução
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
	 * Monta a parte dos valores e o final da instrução
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
	 * A forma mais prática de se criar o vetor à ser criado no banco é dar
	 * aos names dos campos do formulário, os mesmos nomes do campos da tabela.
	 * Pode-se argumentar que é uma falha de segurança, mas pode valer a pena.
	 * 
	 * @api
	 * @param string[] $duplas Vetor indexado pelos nomes dos campos da tabela.
	 * @param string $pk Nome do campo da chave primária.
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
	 * Monta a parte das atribuições de valores da instrução
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
	 * Monta a parte do filtro da instrução
	 * 
	 * Atualmente apenas pode-se filtrar pela chave primária, mas pretende-se
	 * poder filtrar por qualquer campo, assim como com o método de exclusão.
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
	 * Valida os dados à serem inseridos
	 * 
	 * @param string[] $duplas
	 * 
	 * @throws Exception Se o valor for vazio e o campo não permitir nulo, ou se
	 * o valor exceder o limite de caractéres que o campo permite.
	 */
	private function validar(array $duplas){
		$tamanhos=$this->MitiTabela[$this->alias]->getTamanhos();
		$anulaveis=$this->MitiTabela[$this->alias]->getAnulaveis();
		
		foreach($duplas as $i=>$v){
			if(!$anulaveis[$i]&&!$v){
				throw new Exception('Valor vazio.');
			}
			
			if(strlen($v)>$tamanhos[$i]){
				throw new Exception('Limite de caractéres excedido.');
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
	 * Monta a instrução com um vetor
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
	 * Monta a instrução com um valor
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
	 * Impede-se SQL Injection, além de outros tratamentos.
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
	 * Trata o dado referente à chave primária
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
