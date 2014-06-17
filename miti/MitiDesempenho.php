<?php
/**
 * MitiAPI, 2014.
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 */
class MitiDesempenho{
	public function medirTempoExecucao(array $micro,$decimais=6){
		return substr($micro[1]-$micro[0],0,$decimais);
	}
}
