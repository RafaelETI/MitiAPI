<?php
class MitiDataUnit extends MitiUnit{
	private $MitiData;
	
	public function __construct(){
		$this->MitiData=new MitiData();
		
		$this->br2Eua();
		$this->eua2Br();
		$this->obterDiaSemana();
		$this->obterMes();
	}
	
	private function br2Eua(){
		$teste='18/08/1991';
		$this->MitiData->br2Eua($teste);
		$this->afirmar($teste,'1991-08-18',__METHOD__);
	}
	
	private function eua2Br(){
		$teste='1991-08-18';
		$this->MitiData->eua2Br($teste);
		$this->afirmar($teste,'18/08/1991',__METHOD__);
	}
	
	private function obterDiaSemana(){
		$teste='1991-08-23';
		$this->afirmar($this->MitiData->obterDiaSemana($teste),'Sex',__METHOD__);
	}
	
	private function obterMes(){
		$teste='08';
		$this->MitiData->obterMes($teste);
		$this->afirmar($teste,'Agosto',__METHOD__);
	}
}
?>
