<?php
class MitiData{
	public function inverter(&$data){
		$partes=explode('/',$data);
		$data=$partes[2].'-'.$partes[1].'-'.$partes[0];
	}
	
	public function obterMes(&$mes){
		if($mes==1){
			$mes='Janeiro';
		}else if($mes==2){
			$mes='Fevereiro';
		}else if($mes==3){
			$mes='Março';
		}else if($mes==4){
			$mes='Abril';
		}else if($mes==5){
			$mes='Maio';
		}else if($mes==6){
			$mes='Junho';
		}else if($mes==7){
			$mes='Julho';
		}else if($mes==8){
			$mes='Agosto';
		}else if($mes==9){
			$mes='Setembro';
		}else if($mes==10){
			$mes='Outubro';
		}else if($mes==11){
			$mes='Novembro';
		}else if($mes==12){
			$mes='Dezembro';
		}
	}
	
	public function obterDiaSemana(&$dia){
		if($dia==1){
			$dia='Domingo';
		}else if($dia==2){
			$dia='Segunda';
		}else if($dia==3){
			$dia='Terça';
		}else if($dia==4){
			$dia='Quarta';
		}else if($dia==5){
			$dia='Quinta';
		}else if($dia==6){
			$dia='Sexta';
		}else if($dia==7){
			$dia='Sábado';
		}
	}
}
?>

