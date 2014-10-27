<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * ORM (Object Relational Mapping)
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
	 * @var Banco
	 */
	private $Banco;
	
	/**
	 * @var ORM[] Indexado pelo alias de cada tabela externa.
	 */
	private $ORM = array();
	
	/**
	 * @var string
	 */
	private $alias;
	
	/**
	 * @var string
	 */
	private $tabela;
	
	/**
	 * @var Object[]
	 */
	private $campos;
	
	/**
	 * @var string N�o oferece suporte para chave prim�ria composta.
	 */
	private $pk;
	
	/**
	 * @var string[]
	 */
	private $tipos = array();
	
	/**
	 * @var int[]
	 */
	private $tamanhos = array();
	
	/**
	 * @var bool[]
	 */
	private $anulaveis = array();
	
	/**
	 * @var string
	 */
	private $selecoes = '';
	
	/**
	 * @var string
	 */
	private $juncoes = '';
	
	/**
	 * @var string
	 */
	private $filtros = '';
	
	/**
	 * @var string
	 */
	private $grupos = '';
	
	/**
	 * @var string
	 */
	private $ordens = '';
	
	/**
	 * @var string
	 */
	private $limite;
	
	/**
	 * Cria uma conex�o com o banco e mapeia a tabela principal
	 * 
	 * O alias da principal �, preferencialmente, a primeira letra do seu nome.
	 * 
	 * Os aliases das externas s�o preferencialmente tamb�m as primeiras letras,
	 * mas em caso de conflito, pode-se usar outro nome. Eles s�o definidos nas
	 * jun��es, assim como os objetos de mapeamento respectivos.
	 * 
	 * @api
	 * @param string $tabela Nome da tabela principal.
	 * @param string $alias Alias da tabela principal.
	 */
	public function __construct($tabela, $alias){
		$this->Banco = new Banco;
		$this->alias = $alias;
		$this->tabela = $tabela;
		
		$this->mapearCampos()->setPk()->setTipos()->setAnulaveis()->setTamanhos();
	}
	
	public function getBanco(){
		return $this->Banco;
	}
	
	/**
	 * Define o vetor de objetos dos campos
	 * 
	 * @return ORM
	 * 
	 * @throws \UnexpectedValueException Implicitamente.
	 */
	private function mapearCampos(){
		$this->campos = $this->Banco->requisitar("select * from $this->tabela")->mapear();
		return $this;
	}
	
	/**
	 * Define o nome do campo da chave prim�ria
	 * 
	 * @return ORM
	 */
	private function setPk(){
		foreach($this->campos as $Campo){
			if($Campo->flags & 2){
				$this->pk = $Campo->orgname;
				break;
			}
		}
		
		return $this;
	}
	
	public function getPk(){
		return $this->pk;
	}
	
	/**
	 * Define o tipo de cada campo
	 * 
	 * Considera-se apenas duas situa��es: todo n�mero � identificado como
	 * float, e o resto como string. Esses dois valores bastam por motivo de
	 * escape para manuseio do banco.
	 * 
	 * @return ORM
	 */
	private function setTipos(){
		foreach($this->campos as $Campo){
			$this->tipos[$Campo->orgname] = $Campo->flags & 32768? 'float': 'string';
		}
		
		return $this;
	}
	
	public function getTipos(){
		return $this->tipos;
	}
	
	/**
	 * Define a permiss�o de nulidade de cada campo
	 * 
	 * true significa que o campo aceita valor nulo, e false, que n�o aceita.
	 * 
	 * @return ORM
	 */
	private function setAnulaveis(){
		foreach($this->campos as $Campo){
			$this->anulaveis[$Campo->orgname] = $Campo->flags & 1? false: true;
		}
		
		return $this;
	}
	
	public function getAnulaveis(){
		return $this->anulaveis;
	}
	
	/**
	 * Define o tamanho m�ximo de cada campo
	 * 
	 * @return ORM
	 */
	private function setTamanhos(){
		foreach($this->campos as $Campo){
			$this->tamanhos[$Campo->orgname] = $Campo->length;
		}
		
		return $this;
	}
	
	public function getTamanhos(){
		return $this->tamanhos;
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
	 * Pode-se argumentar que � uma falha de seguran�a.
	 * 
	 * @api
	 * @param string[] $tupla Vetor indexado pelos nomes dos campos da tabela.
	 * @return Banco
	 * @throws \UnexpectedValueException Implicitamente.
	 */
	public function criar(array $tupla){
		$sql = '';
		$sql = $this->montarCampos($sql, $tupla);
		$sql = $this->montarValores($sql, $tupla);
		return $this->Banco->requisitar($sql);
	}
	
	/**
	 * Monta o in�cio e a parte dos campos da instru��o
	 * 
	 * @param string $sql
	 * @param string[] $tupla
	 * @return string
	 */
	private function montarCampos($sql, array $tupla){
		$sql = "insert into $this->tabela (";
		
		$campos = array();
		foreach($tupla as $campo => $valor){$campos[] = $campo;}
		
		return $sql . implode(', ', $campos) . ')';
	}
	
	/**
	 * Monta a parte dos valores e o final da instru��o
	 * 
	 * @param string $sql
	 * @param string[] $tupla
	 * @return string
	 */
	private function montarValores($sql, array $tupla){
		$this->validar($tupla);
		$tupla = $this->tratar($tupla);
		
		$sql .= ' values (';
		
		$values = array();
		foreach($tupla as $valor){$values[] = $valor;}
		
		return $sql . implode(', ', $values) . ')';
	}
	
	/**
	 * Atualiza um registro na tabela (Update do CRUD)
	 * 
	 * A forma mais pr�tica de se criar o vetor � ser enviado ao banco � dar
	 * aos names dos campos do formul�rio, os mesmos nomes do campos da tabela.
	 * Pode-se argumentar que � uma falha de seguran�a, mas pode valer a pena.
	 * 
	 * @api
	 * @param string[] $tupla Vetor indexado pelos nomes dos campos da tabela.
	 * @param string $pk Nome do campo da chave prim�ria.
	 * @return Banco
	 * @throws \UnexpectedValueException Implicitamente.
	 */
	public function atualizar(array $tupla, $pk){
		$sql = '';
		$sql = $this->montarAtribuicoes($sql, $tupla);
		$sql = $this->montarWhereAlteracao($sql, $pk);
		return $this->Banco->requisitar($sql);
	}
	
	/**
	 * Monta a parte das atribui��es de valores da instru��o
	 * 
	 * @param string $sql
	 * @param string[] $tupla
	 * @return string
	 */
	private function montarAtribuicoes($sql, array $tupla){
		$this->validar($tupla);
		$tupla = $this->tratar($tupla);
		
		$sql = "update $this->tabela set ";
		
		$atribuicoes = array();
		foreach($tupla as $campo => $valor){$atribuicoes[] = "$campo = $valor";}
		
		return $sql . implode(', ', $atribuicoes);
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
	private function montarWhereAlteracao($sql, $pk){
		$pk = $this->tratarPk($pk);
		return "$sql where $this->pk = $pk";
	}
	
	/**
	 * Valida os dados � serem inseridos
	 * 
	 * @param string[] $tupla
	 * @throws \UnexpectedValueException
	 */
	private function validar(array $tupla){
		foreach($tupla as $campo => $valor){
			if(!$this->anulaveis[$campo] && ($valor === '' || $valor === null)){
				throw new \UnexpectedValueException("Valor vazio para o campo '$campo'.");
			}
			
			if(strlen($valor) > $this->tamanhos[$campo]){
				throw new \UnexpectedValueException("Limite de caract�res excedido para o campo '$campo'.");
			}
		}
	}
	
	/**
	 * Exclui um registro na tabela (Delete do CRUD)
	 * 
	 * @api
	 * @param mixed|mixed[] $filtro Se for um vetor, deve conter apenas uma dupla.
	 * @return Banco
	 * @throws \UnexpectedValueException Implicitamente.
	 */
	public function deletar($filtro){
		$sql = is_array($filtro)? $this->montarExclusaoArray($filtro): $this->montarExclusaoScalar($filtro);
		return $this->Banco->requisitar($sql);
	}
	
	private function montarExclusaoArray(array $dupla){
		$dupla = $this->tratar($dupla);
		
		foreach($dupla as $campo => $valor){
			$sql = "delete from $this->tabela where $campo = $valor";
			break;
		}
		
		return $sql;
	}
	
	private function montarExclusaoScalar($pk){
		$pk = $this->tratarPk($pk);
		return "delete from $this->tabela where $this->pk = $pk";
	}
	
	/**
	 * Trata os dados � serem inseridos no banco
	 * 
	 * Impede-se SQL Injection, al�m de outros tratamentos.
	 * 
	 * @param string[] $tupla
	 * @return string[]
	 */
	private function tratar(array $tupla){
		foreach($tupla as $campo => &$valor){
			if($valor === '' || $valor === null){
				$valor = 'null';
			}else{
				$this->tipos[$campo] === 'string'?
					$valor = '"' . $this->Banco->escapar($valor) . '"':
					settype($valor, $this->tipos[$campo])
				;
			}
		}
		
		return $tupla;
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
		$this->tipos[$this->pk] === 'string'?
			$pk = '"' . $this->Banco->escapar($pk) . '"':
			settype($pk, $this->tipos[$this->pk])
		;
		
		return $pk;
	}
	
	/**
	 * Seleciona um campo de uma tabela
	 * 
	 * Para selecionar-se mais de um campo, chamar esse m�todo quantas vezes
	 * forem necess�rias.
	 * 
	 * @api
	 * @param string $alias De qualquer tabela.
	 * @param string $campo De qualquer tabela.
	 * 
	 * @param string $aliasCampo Importante para resolver conflitos com campos
	 * de tabelas juntadas ou para simplificar nomes criados � partir do uso de
	 * fun��es do banco.
	 * 
	 * @param string $funcao Fun��o do banco a ser chamada passando $alias.$campo
	 * como %s.
	 * 
	 * @return ORM
	 */
	public function selecionar($alias, $campo, $aliasCampo = '', $funcao = '%s'){
		$separador = $this->selecoes? ', ': '';
		$campo = sprintf($funcao, "$alias.$campo");
		if($aliasCampo){$aliasCampo = " as $aliasCampo";}
		$this->selecoes .= "$separador $campo $aliasCampo ";
		return $this;
	}
	
	/**
	 * Junta a tabela principal com uma tabela externa
	 * 
	 * Para juntar-se mais de uma tabela, chamar esse m�todo quantas vezes forem
	 * necess�rias.
	 * 
	 * @api
	 * @param string $externa Nome da tabela externa � ser juntada.
	 * @param string $alias Da tabela externa.
	 * @param string $aliasCampo
	 * @param string $campo
	 * @param string $aliasCampoExterna
	 * @param string $campoExterna
	 * @param string $juncao join, left join, etc.
	 * @return ORM
	 */
	public function juntar($externa, $alias, $aliasCampo, $campo, $aliasCampoExterna, $campoExterna, $juncao = 'join'){
		$this->ORM[$alias] = new ORM($externa, $alias);
		
		$this->juncoes .=
			"$juncao $externa $alias"
			." on $aliasCampo.$campo"
			." = $aliasCampoExterna.$campoExterna "
		;
		
		return $this;
	}
	
	public function juntarEsquerda($externa, $alias, $aliasCampo, $campo, $aliasCampoExterna, $campoExterna){
		$this->juntar($externa, $alias, $aliasCampo, $campo, $aliasCampoExterna, $campoExterna, 'left join');
		return $this;
	}
	
	public function juntarDireita($externa, $alias, $aliasCampo, $campo, $aliasCampoExterna, $campoExterna){
		$this->juntar($externa, $alias, $aliasCampo, $campo, $aliasCampoExterna, $campoExterna, 'right join');
		return $this;
	}
	
	/**
	 * Filtra os registros de uma sele��o
	 * 
	 * Cham�-lo apenas uma vez. Na necessidade de mais de um filtro, usar os
	 * outros m�todos de filtragem.
	 * 
	 * Esse docblock vale tamb�m, em grande parte, para os m�todos eFiltrar() e
	 * ouFiltrar().
	 * 
	 * @api
	 * @param string $alias De qualquer tabela.
	 * @param string $campo De qualquer tabela.
	 * @param string $operador =, !=, like, >, >=, etc.
	 * @param mixed $valor
	 * 
	 * @param string $funcao Fun��o do banco a ser chamada passando $alias.$campo
	 * como %s.
	 * 
	 * @param string $separador Utilizado, preferencialmente, pelos outros m�todos
	 * de filtragem.
	 * 
	 * @return ORM
	 */
	public function filtrar($alias, $campo, $operador, $valor, $funcao = '%s', $separador = ''){
		$valor = $this->tratarLeitura($alias, $campo, $operador, $valor);
		$campo = sprintf($funcao, "$alias.$campo");
		$this->filtros .= "$separador $campo $operador $valor ";
		return $this;
	}
	
	public function eFiltrar($alias, $campo, $operador, $valor, $funcao = '%s'){
		$this->filtrar($alias, $campo, $operador, $valor, $funcao, 'and');
		return $this;
	}
	
	public function ouFiltrar($alias, $campo, $operador, $valor, $funcao = '%s'){
		$this->filtrar($alias, $campo, $operador, $valor, $funcao, 'or');
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
	private function tratarLeitura($alias, $campo, $operador, $valor){
		$tipos = $alias === $this->alias? $this->tipos: $this->ORM[$alias]->getTipos();
		
		if($operador === 'like'){
			$valor = "'%{$this->Banco->escapar($valor)}%'";
		}else if($tipos[$campo] === 'string'){
			$valor = "'{$this->Banco->escapar($valor)}'";
		}else{
			settype($valor, $tipos[$campo]);
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
	public function agrupar($alias, $campo){
		$separador = $this->grupos? ', ': '';
		$this->grupos .= "$separador $alias.$campo ";
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
	public function ordenar($alias, $campo, $ordens){
		$separador = $this->ordens? ', ': '';
		$this->ordens .= "$separador $alias.$campo $ordens ";
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
		$this->ordens = 'rand()';
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
	public function limitar($quantidade, $inicio = ''){
		if(!$quantidade){return $this;}
		if($inicio !== ''){$inicio .= ', ';}
		$this->limite = $inicio.$quantidade;
		return $this;
	}
	
	/**
	 * L� registros da tabela (Read do CRUD)
	 * 
	 * @api
	 * @return Banco
	 * @throws \UnexpectedValueException Implicitamente.
	 */
	public function ler(){
		$this->filtros = $this->concatenarClausula($this->filtros, 'where');
		$this->grupos = $this->concatenarClausula($this->grupos, 'group by');
		$this->ordens = $this->concatenarClausula($this->ordens, 'order by');
		$this->limite = $this->concatenarClausula($this->limite, 'limit');
		
		$sql =
			"select $this->selecoes"
			."from $this->tabela $this->alias "
			.$this->juncoes
			.$this->filtros
			.$this->grupos
			.$this->ordens
			.$this->limite
		;
		
		return $this->Banco->requisitar($sql);
	}
	
	/**
	 * Concatena cl�usula SQL �s montagens anteriores das instru��es
	 * 
	 * @param string $propriedade
	 * @param string $sql
	 * @return string
	 */
	private function concatenarClausula($propriedade, $sql){
		if($propriedade && strpos($propriedade, $sql) === false){
			$propriedade = "$sql $propriedade";
		}
		
		return $propriedade;
	}
	
	/**
	 * Limpa todas as instru��es SQL montadas em propriedades
	 * 
	 * @api
	 * @return ORM
	 */
	public function zerar(){
		$this->selecoes = '';
		$this->juncoes = '';
		$this->filtros = '';
		$this->grupos = '';
		$this->ordens = '';
		$this->limite = '';
		
		return $this;
	}
}
