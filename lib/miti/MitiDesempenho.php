<?php
class MitiDesempenho{
	public function medirTempoExecucao(array $micro,$decimais=6){
		return substr($micro[1]-$micro[0],0,$decimais);
	}
}
