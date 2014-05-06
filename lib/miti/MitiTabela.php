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
		$this->obterCampos();
		$this->setPk();
		$this->setTipos();
		$this->setAnulaveis();
		$this->setTamanhos();
	}
	
	private function obterCampos(){
		$MitiBD=new MitiBD;
		
		$this->campos=$MitiBD
			->requisitar('select * from '.$this->nome)
			->obterCampos()
		;
	}
	
	private function setPk(){
		foreach($this->campos as $o){
			if($o->flags&2){
				$this->pk=$o->orgname;
				break;
			}
		}
	}
	
	private function setTipos(){
		foreach($this->campos as $o){
			if($o->flags&32768){
				$this->tipos[$o->orgname]='float';
			}else{
				$this->tipos[$o->orgname]='string';
			}
		}
	}
	
	private function setAnulaveis(){
		foreach($this->campos as $o){
			if($o->flags&1){
				$this->anulaveis[$o->orgname]=false;
			}else{
				$this->anulaveis[$o->orgname]=true;
			}
		}
	}
	
	private function setTamanhos(){
		foreach($this->campos as $o){
			$this->tamanhos[$o->orgname]=$o->length;
		}
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
