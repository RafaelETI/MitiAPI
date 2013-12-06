<?php
class MitiData{
	public function inverter(&$data){
		$partes=explode('/',$data);
		$data=$partes[2].'-'.$partes[1].'-'.$partes[0];
	}
}
?>
