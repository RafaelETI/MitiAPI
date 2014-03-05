<?php
class MitiTabelaUnit extends MitiUnit{
	private $MitiTabela;
	
	public function __construct(){
		$this->MitiTabela=new MitiTabela('mitiunit');
		
		$this->getNome();
		$this->getTipos();
		$this->getAnulaveis();
		$this->getTamanhos();
		$this->getPkCampo();
		$this->getPkTipo();
	}
	
	private function getNome(){
		$this->afirmar($this->MitiTabela->getNome(),'mitiunit',__METHOD__);
	}
	
	private function getTipos(){
		$this->afirmar($this->MitiTabela->getTipos(),array('id'=>'float','nome'=>'string','idade'=>'float'),__METHOD__);
	}
	
	private function getAnulaveis(){
		$this->afirmar($this->MitiTabela->getAnulaveis(),array('id'=>false,'nome'=>false,'idade'=>true),__METHOD__);
	}
	
	private function getTamanhos(){
		$this->afirmar($this->MitiTabela->getTamanhos(),array('id'=>3,'nome'=>30,'idade'=>3),__METHOD__);
	}
	
	private function getPkCampo(){
		$this->afirmar($this->MitiTabela->getPkCampo(),'id',__METHOD__);
	}
	
	private function getPkTipo(){
		$this->afirmar($this->MitiTabela->getPkTipo(),'float',__METHOD__);
	}
}
?>
