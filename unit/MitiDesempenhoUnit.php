<?php
class MitiDesempenhoUnit extends MitiUnit{
	private $MitiDesempenho;
	
	public function __construct(){
		$this->MitiDesempenho=new MitiDesempenho();
		$this->medirTempoExecucao();
	}

	private function medirTempoExecucao(){
		$teste=array(1391905903.114,1391905984.1241);
		$this->afirmar($this->MitiDesempenho->medirTempoExecucao($teste),'81.010',__METHOD__);
	}
}
?>
