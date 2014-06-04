<?php
class MitiTabela{
	private $nome;
	private $campos;
	private $pk;
	private $tipos=array();
	private $anulaveis=array();
	private $tamanhos=array();
	
	public function __construct($nome){
		$this->nome=$nome;
		
		$this
			->obterCampos()
			->setPk()
			->setTipos()
			->setAnulaveis()
			->setTamanhos()
		;
	}
	
	private function obterCampos(){
		$MitiBD=MitiBD::getInstance();
		
		$this->campos=$MitiBD
			->requisitar('select * from '.$this->nome)
			->obterCampos()
		;
		
		return $this;
	}
	
	private function setPk(){
		foreach($this->campos as $o){
			if($o->flags&2){
				$this->pk=$o->orgname;
				break;
			}
		}
		
		return $this;
	}
	
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
	
	private function setTamanhos(){
		foreach($this->campos as $o){
			$this->tamanhos[$o->orgname]=$o->length;
		}
		
		return $this;
	}
	
	public function getNome(){
		return $this->nome;
	}
	
	public function getTipos(){
		return $this->tipos;
	}
	
	public function getAnulaveis(){
		return $this->anulaveis;
	}
	
	public function getTamanhos(){
		return $this->tamanhos;
	}
	
	public function getPkCampo(){
		return $this->pk;
	}
	
	public function getPkTipo(){
		return $this->tipos[$this->pk];
	}
}
