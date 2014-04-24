<?php
class MitiDesempenho{
	public function medirTempoExecucao(array $micro,$decimais=6){
		$micro_total=$micro[1]-$micro[0];
		$micro_total=substr($micro_total,0,$decimais);
		
		return $micro_total;
	}
}
