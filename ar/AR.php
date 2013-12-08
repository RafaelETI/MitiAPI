<?php
class AR{
	public function getTabela(){
		return $this->tabela;
	}
	
	public function getCampos(){
		return $this->campos;
	}
	
	public function getTipos(){
		return $this->tipos;
	}
	
	public function getTamanhos(){
		return $this->tamanhos;
	}
	
	public function getPkCampo(){
		return $this->campos[$this->pk];
	}
	
	public function getPkTipo(){
		return $this->tipos[$this->pk];
	}
}
?>
