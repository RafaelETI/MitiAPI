<?php
abstract class AR{
	protected $tabela;
	protected $tipos=array();
	protected $anulaveis=array();
	protected $tamanhos=array();
	protected $pk;

	public function getTabela(){
		return $this->tabela;
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
?>
