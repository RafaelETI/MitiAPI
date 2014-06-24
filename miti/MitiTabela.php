<?php
/**
 * MitiAPI, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * ORM autom�tico
 * 
 * N�o faz parte da API. Serve apenas para uso interno da pr�pria API.
 */
class MitiTabela{
	/**
	 * @var string Nome da tabela.
	 */
	private $nome;
	
	/**
	 * @var Object[] Vetor os com objetos que representam os campos da tabela.
	 */
	private $campos;
	
	/**
	 * @var string Chave prim�ria da tabela. N�o foi testado com chave prim�ria
	 * m�ltipla. Provavelmente n�o funciona.
	 */
	private $pk;
	
	/**
	 * @var string[] Vetor com os tipos dos campos.
	 */
	private $tipos=array();
	
	/**
	 * @var bool[] Vetor com permiss�es de nulidade dos campos.
	 */
	private $anulaveis=array();
	
	/**
	 * @var int[] Vetor com os tamanhos m�ximos dos campos.
	 */
	private $tamanhos=array();
	
	/**
	 * Define o nome da tabela
	 * 
	 * @param string $nome
	 */
	public function __construct($nome){
		$this->nome=$nome;
		
		$this
			->mapearCampos()
			->setPk()
			->setTipos()
			->setAnulaveis()
			->setTamanhos()
		;
	}
	
	/**
	 * Define o vetor de objetos dos campos
	 * 
	 * @return \MitiTabela
	 * 
	 * @throws Exception Implicitamente. Por causa dessa requisi��o, deve-se
	 * instanciar todo objeto que realizar um ORM, dentro de um bloco try...catch.
	 */
	private function mapearCampos(){
		$MitiBD=new MitiBD;
		
		$this->campos=$MitiBD
			->requisitar('select * from '.$this->nome)
			->obterCampos()
		;
		
		return $this;
	}
	
	/**
	 * Define o nome do campo da chave prim�ria
	 * 
	 * @return \MitiTabela
	 */
	private function setPk(){
		foreach($this->campos as $o){
			if($o->flags&2){
				$this->pk=$o->orgname;
				break;
			}
		}
		
		return $this;
	}
	
	/**
	 * Define o tipo de cada campo
	 * 
	 * Considera-se apenas duas situa��es: todo n�mero � identificado como
	 * float, e o resto como string. Esses dois valores bastam por motivo de
	 * escape para manuseio do banco.
	 * 
	 * @return \MitiTabela
	 */
	private function setTipos(){
		foreach($this->campos as $o){
			if($o->flags&32768){
				$this->tipos[$o->orgname]='float';
			}else{
				$this->tipos[$o->orgname]='string';
			}
		}
		
		return $this;
	}
	
	/**
	 * Define a permiss�o de nulidade de cada campo
	 * 
	 * true significa que o campo aceita valor nulo, e false, que n�o aceita.
	 * 
	 * @return \MitiTabela
	 */
	private function setAnulaveis(){
		foreach($this->campos as $o){
			if($o->flags&1){
				$this->anulaveis[$o->orgname]=false;
			}else{
				$this->anulaveis[$o->orgname]=true;
			}
		}
		
		return $this;
	}
	
	/**
	 * Define o tamanho m�ximo de cada campo
	 * 
	 * @return \MitiTabela
	 */
	private function setTamanhos(){
		foreach($this->campos as $o){
			$this->tamanhos[$o->orgname]=$o->length;
		}
		
		return $this;
	}
	
	/**
	 * Retorna o nome da tabela
	 * 
	 * @return string
	 */
	public function getNome(){
		return $this->nome;
	}
	
	/**
	 * Retorna o vetor com os tipos dos campos
	 * 
	 * @return string[]
	 */
	public function getTipos(){
		return $this->tipos;
	}
	
	/**
	 * Retorna o vetor com as permiss�es de nulidade dos campos
	 * 
	 * @return bool[]
	 */
	public function getAnulaveis(){
		return $this->anulaveis;
	}
	
	/**
	 * Retorna o vetor com os tamanhos m�ximos dos campos 
	 * 
	 * @return int[]
	 */
	public function getTamanhos(){
		return $this->tamanhos;
	}
	
	/**
	 * Retorna o nome do campo da chave prim�ria
	 * 
	 * @return string
	 */
	public function getPkCampo(){
		return $this->pk;
	}
	
	/**
	 * Retorna o tipo do campo da chave prim�ria
	 * 
	 * @return string
	 */
	public function getPkTipo(){
		return $this->tipos[$this->pk];
	}
}
