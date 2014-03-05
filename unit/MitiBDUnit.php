<?php
class MitiBDUnit extends MitiUnit{
	private $MitiBD;
	
	public function __construct(){
		$this->MitiBD=new MitiBD();
		
		$this->escaparString();
		$this->escaparArray();
		$this->requisitar();
		$this->getAfetados();
		$this->getId();
		$this->obterQuantidade();
		$this->obterCampos();
	}
	
	private function escaparString(){
		$teste='\'"\\';
		$this->MitiBD->escapar($teste);
		$this->afirmar($teste,'\\\'\\"\\\\',__METHOD__);
	}
	
	private function escaparArray(){
		$teste=array("'",'"','\\');
		$this->MitiBD->escapar($teste);
		$this->afirmar($teste,array("\\'",'\\"','\\\\'),__METHOD__);
	}
	
	private function requisitar(){
		$this->MitiBD->requisitar('select id from mitiunit');
		$teste=$this->MitiBD->obterAssoc();
		$this->afirmar($teste['id'],'1',__METHOD__);
	}
	
	public function getAfetados(){
		$this->afirmar($this->MitiBD->getAfetados(),1,__METHOD__);
	}
	
	public function getId(){
		$this->afirmar($this->MitiBD->getId(),0,__METHOD__);
	}
	
	private function obterQuantidade(){
		$this->afirmar($this->MitiBD->obterQuantidade(),1,__METHOD__);
	}
	
	private function obterCampos(){
		$teste=$this->MitiBD->obterCampos();
		$this->afirmar($teste[0]->flags,49699,__METHOD__);
	}
}
?>
