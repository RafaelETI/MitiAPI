<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace Miti;

/**
 * ORM (Object Relational Mapping)
 * 
 * H� uma depend�ncia com a classe Tabela que � a que na verdade faz o
 * mapeamento com a tabela do banco. Essa classe � respons�vel por realizar os
 * manuseios no banco baseando-se nesse mapeamento.
 * 
 * A caracter�stica mais vis�vel para o usu�rio, ao utilizar essa classe, � a
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
class ORM{
	/**
	 * @var string Alias da tabela principal da requisi��o.
	 */
	private $alias;
	
	/**
	 * @var Tabela[] Indexado pelo alias de cada tabela. O objeto da tabela
	 * principal fica na primeira posi��o, e as externas no resto.
	 */
	private $Tabela=array();
	
	/**
	 * @var BD
	 */
	private $BD;
	
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
		$this->Tabela[$this->alias]=new Tabela($tabela);
		
		$this->BD=new BD;
	}
	
	/**
	 * Cria um registro na tabela (Create do CRUD)
	 * 
	 * Uma das principais informa��es conseguidas atrav�s do retorno � o valor
	 * do id auto incrementado gerado pelo banco para o registro que acabara de
	 * ser inserido.
	 * 
	 * A forma mais pr�tica de se criar o vetor � ser enviado ao banco � dar
	 * aos names dos campos do formul�rio, os mesmos nomes do campos da tabela.
	 * Pode-se argumentar que � uma falha de seguran�a, mas pode valer a pena.
	 * 
	 * @api
	 * @param string[] $duplas Vetor indexado pelos nomes dos campos da tabela.
	 * @return BD
	 * @throws \Exception Implicitamente.
	 */
	public function criar(array $duplas){
		$sql='';
		$sql=$this->montarCampos($sql,$duplas);
		$sql=$this->montarValores($sql,$duplas);
		return $this->BD->requisitar($sql);
	}
	
	/**
	 * Monta o in�cio e a parte dos campos da instru��o
	 * 
	 * @param string $sql
	 * @param string[] $duplas
	 * @return string
	 */
	private function montarCampos($sql,array $duplas){
		$sql='insert into '.$this->Tabela[$this->alias]->getNome().'(';
		
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
	 * A forma mais pr�tica de se criar o vetor � ser enviado ao banco � dar
	 * aos names dos campos do formul�rio, os mesmos nomes do campos da tabela.
	 * Pode-se argumentar que � uma falha de seguran�a, mas pode valer a pena.
	 * 
	 * @api
	 * @param string[] $duplas Vetor indexado pelos nomes dos campos da tabela.
	 * @param string $pk Nome do campo da chave prim�ria.
	 * @return BD
	 * @throws \Exception Implicitamente.
	 */
	public function atualizar(array $duplas,$pk){
		$sql='';
		$sql=$this->montarAtribuicoes($sql,$duplas);
		$sql=$this->montarWhereAlteracao($sql,$pk);
		return $this->BD->requisitar($sql);
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
		
		$sql='update '.$this->Tabela[$this->alias]->getNome().' set ';
		
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
		return $sql.' where '.$this->Tabela[$this->alias]->getPkCampo().'='.$pk;
	}
	
	/**
	 * Valida os dados � serem inseridos
	 * 
	 * @param string[] $duplas
	 * 
	 * @throws \Exception Se o valor for vazio e o campo n�o permitir nulo, ou se
	 * o valor exceder o limite de caract�res que o campo permite.
	 */
	private function validar(array $duplas){
		$tamanhos=$this->Tabela[$this->alias]->getTamanhos();
		$anulaveis=$this->Tabela[$this->alias]->getAnulaveis();
		
		foreach($duplas as $i=>$v){
			if(!$anulaveis[$i]&&!$v){
				throw new \Exception("Valor vazio para o campo '$i'.");
			}
			
			if(strlen($v)>$tamanhos[$i]){
				throw new \Exception("Limite de caract�res excedido para o campo '$i'.");
			}
		}
	}
	
	/**
	 * Exclui um registro na tabela (Delete do CRUD)
	 * 
	 * @api
	 * @param mixed|mixed[] $filtro Se for um vetor, deve conter apenas uma dupla.
	 * @return BD
	 * @throws \Exception Implicitamente.
	 */
	public function deletar($filtro){
		if(is_array($filtro)){
			$sql=$this->montarExclusaoArray($filtro);
		}else{
			$sql=$this->montarExclusaoScalar($filtro);
		}
		
		return $this->BD->requisitar($sql);
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
				'delete from '.$this->Tabela[$this->alias]->getNome()
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
			'delete from '.$this->Tabela[$this->alias]->getNome()
			.' where '.$this->Tabela[$this->alias]->getPkCampo().'='.$pk
		;
	}
	
	/**
	 * Trata os dados � serem inseridos no banco
	 * 
	 * Impede-se SQL Injection, al�m de outros tratamentos.
	 * 
	 * @param string[] $duplas
	 * @return string[]
	 */
	private function tratar(array $duplas){
		$tipos=$this->Tabela[$this->alias]->getTipos();
		
		foreach($duplas as $i=>$v){
			if($v===''){
				$duplas[$i]='null';
			}else{
				if($tipos[$i]==='string'){
					$duplas[$i]=$this->BD->escapar($v);
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
		if($this->Tabela[$this->alias]->getPkTipo()==='string'){
			$pk=$this->BD->escapar($pk);
			$pk='"'.$pk.'"';
		}else{
			settype($pk,$this->Tabela[$this->alias]->getPkTipo());
		}
		
		return $pk;
	}
	
	/**
	 * Seleciona um campo de uma tabela
	 * 
	 * Para selecionar-se mais de um campo, chamar esse m�todo quantas vezes
	 * forem necess�rias.
	 * 
	 * Pode-se usar fun��es do banco de dados nessa sele��o. Defin�-los no
	 * segundo par�metro. Nesse caso, deixar o primeiro par�metro vazio.
	 * 
	 * @api
	 * @param string $alias De qualquer tabela.
	 * @param string $campo De qualquer tabela.
	 * 
	 * @param string $alias_campo Importante para resolver conflitos com campos
	 * de tabelas juntadas ou para simplificar nomes criados � partir do uso de
	 * fun��es do banco.
	 * 
	 * @return ORM
	 * 
	 * @todo Melhorar a forma de chamar fun��es do banco. N�o � bom ter que
	 * deixar o primeiro par�metro vazio.
	 */
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
	
	/**
	 * Junta a tabela principal com uma tabela externa
	 * 
	 * Para juntar-se mais de uma tabela, chamar esse m�todo quantas vezes forem
	 * necess�rias.
	 * 
	 * @api
	 * @param string $juncao join, left join, etc.
	 * @param string $externa Nome da tabela externa � ser juntada.
	 * @param string $alias Da tabela externa.
	 * @param string $alias_campo
	 * @param string $campo
	 * @param string $alias_campo_externa
	 * @param string $campo_externa
	 * @return ORM
	 */
	public function juntar(
		$juncao,$externa,$alias,$alias_campo,$campo,$alias_campo_externa,$campo_externa
	){
		$this->Tabela[$alias]=new Tabela($externa);
		
		$this->juncoes.=
			$juncao.' '.$externa.' '.$alias
			.' on '.$alias_campo.'.'.$campo
			.'='.$alias_campo_externa.'.'.$campo_externa.' '
		;
		
		return $this;
	}
	
	/**
	 * Filtra os registros de uma sele��o
	 * 
	 * Cham�-lo apenas uma vez. Na necessidade de mais de um filtro, usar os
	 * outros m�todos de filtragem.
	 * 
	 * @api
	 * @param string $alias De qualquer tabela.
	 * @param string $campo De qualquer tabela.
	 * @param string $operador =, !=, like, >, >=, etc.
	 * @param mixed $valor
	 * @param string $separador Utilizado preferencialmente por m�todo interno.
	 * @return ORM
	 */
	public function filtrar($alias,$campo,$operador,$valor,$separador=''){
		$valor=$this->tratarLeitura($alias,$campo,$operador,$valor);
		$this->filtros.=$separador.' '.$alias.'.'.$campo.' '.$operador.' '.$valor.' ';
		return $this;
	}
	
	/**
	 * Filtra os registros de uma sele��o
	 * 
	 * Une-se ao filtro anterior com a opera��o and.
	 * 
	 * Para criar mais de um filtro, chamar esse m�todo quantas vezes forem
	 * necess�rias.
	 * 
	 * � uma possibilidade de interface mais intuitiva para o usu�rio, j� que
	 * abstrai a opera��o no nome do m�todo, e n�o em uma passagem de par�metro.
	 * 
	 * @api
	 * @param string $alias De qualquer tabela.
	 * @param string $campo De qualquer tabela.
	 * @param string $operador =, !=, like, >, >=, etc.
	 * @param mixed $valor
	 * @return ORM
	 */
	public function eFiltrar($alias,$campo,$operador,$valor){
		$this->filtrar($alias,$campo,$operador,$valor,'and');
		return $this;
	}
	
	/**
	 * Filtra os registros de uma sele��o
	 * 
	 * Une-se ao filtro anterior com a opera��o or.
	 * 
	 * Para criar mais de um filtro, chamar esse m�todo quantas vezes forem
	 * necess�rias.
	 * 
	 * � uma possibilidade de interface mais intuitiva para o usu�rio, j� que
	 * abstrai a opera��o no nome do m�todo, e n�o em uma passagem de par�metro.
	 * 
	 * @api
	 * @param string $alias De qualquer tabela.
	 * @param string $campo De qualquer tabela.
	 * @param string $operador =, !=, like, >, >=, etc.
	 * @param mixed $valor
	 * @return ORM
	 */
	public function ouFiltrar($alias,$campo,$operador,$valor){
		$this->filtrar($alias,$campo,$operador,$valor,'or');
		return $this;
	}
	
	/**
	 * Trata os dados passados em um filtro
	 * 
	 * Impede-se SQL Injection, al�m de outros tratamentos.
	 * 
	 * Em caso de opera��o like, sempre considera curingas dos dois lados do
	 * dado.
	 * 
	 * @param string $alias
	 * @param string $campo
	 * @param string $operador
	 * @param mixed $valor
	 * @return mixed
	 */
	private function tratarLeitura($alias,$campo,$operador,$valor){
		$tipos=$this->Tabela[$alias]->getTipos();
		
		if($operador==='like'){
			$valor='"%'.$this->BD->escapar($valor).'%"';
		}else if($tipos[$campo]==='string'){
			$valor='"'.$this->BD->escapar($valor).'"';
		}else{
			settype($valor,$tipos[$campo]);
		}
		
		return $valor;
	}
	
	/**
	 * Agrupa os registros selecionados, � partir de um campo
	 * 
	 * Para agrupar mais de um campo, chamar esse m�todo quantas vezes forem
	 * necess�rias.
	 * 
	 * Geralmente usado em conjunto com uma fun��o de agrega��o do banco.
	 * 
	 * @api
	 * @param string $alias De qualquer tabela.
	 * @param string $campo De qualquer tabela.
	 * @return ORM
	 */
	public function agrupar($alias,$campo){
		$separador='';
		if($this->grupos){
			$separador=',';
		}
		
		$this->grupos.=$separador.$alias.'.'.$campo.' ';
		return $this;
	}
	
	/**
	 * Ordena os registros selecionados, � partir de um campo
	 * 
	 * Para ordenar mais de um campo, chamar esse m�todo quantas vezes forem
	 * necess�rias.
	 * 
	 * @api
	 * @param string $alias De qualquer tabela.
	 * @param string $campo De qualquer tabela.
	 * @param string $ordens asc ou desc.
	 * @return ORM
	 */
	public function ordenar($alias,$campo,$ordens){
		$separador='';
		if($this->ordens){
			$separador=',';
		}
		
		$this->ordens.=$separador.$alias.'.'.$campo.' '.$ordens.' ';
		return $this;
	}
	
	/**
	 * Ordena os registros selecionados, aleatoriamente
	 * 
	 * N�o criar outras ordens quando usar este m�todo, podem surgir resultados
	 * inesperados.
	 * 
	 * @api
	 * @return ORM
	 */
	public function ordenarAleatoriamente(){
		$this->ordens='rand()';
		return $this;
	}
	
	/**
	 * Limita a quantidade e posi��es dos registros.
	 * 
	 * O primeiro registro da sele��o tem a posi��o zero.
	 * 
	 * A ordem dos par�metros � o contr�rio da linguagem SQL para que d� menos
	 * trabalho ao usu�rio informar apenas uma quantidade, sem in�cio. O que �
	 * muito comum.
	 * 
	 * @api
	 * @param int $quantidade
	 * @param int $inicio Incluindo zero.
	 * @return ORM
	 */
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
	
	/**
	 * L� registros da tabela (Read do CRUD)
	 * 
	 * @api
	 * @return BD
	 * @throws \Exception Implicitamente.
	 */
	public function ler(){
		$this->filtros=$this->concatenarClausula($this->filtros,'where');
		$this->grupos=$this->concatenarClausula($this->grupos,'group by');
		$this->ordens=$this->concatenarClausula($this->ordens,'order by');
		$this->limite=$this->concatenarClausula($this->limite,'limit');
		
		$sql=
			'select '.$this->campos
			.'from '.$this->Tabela[$this->alias]->getNome().' '.$this->alias.' '
			.$this->juncoes
			.$this->filtros
			.$this->grupos
			.$this->ordens
			.$this->limite
		;
		
		return $this->BD->requisitar($sql);
	}
	
	/**
	 * Concatena cl�usula SQL �s montagens anteriores das instru��es
	 * 
	 * @param string $propriedade
	 * @param string $sql
	 * @return string
	 */
	private function concatenarClausula($propriedade,$sql){
		if($propriedade&&strpos($propriedade,$sql)===false){
			$propriedade=$sql.' '.$propriedade;
		}
		
		return $propriedade;
	}
}
