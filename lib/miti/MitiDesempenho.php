<?php
class MitiDesempenho{
	public function medirTempoExecucao($micro,$decimais=6){
		$micro_total=$micro[1]-$micro[0];
		$micro_total=substr($micro_total,0,$decimais);
		
		if(strlen($micro_total)==1){$micro_total.='.0000';}
		
		return $micro_total;
	}
}
?>
