<?php
class MitiData{
	public function br2Eua(&$data){
		if(!$data){
			return;
		}
		
		$partes=explode('/',$data);
		$data=$partes[2].'-'.$partes[1].'-'.$partes[0];
	}
	
	public function eua2Br(&$data){
		if(!$data){
			return;
		}
		
		$partes=explode('-',$data);
		$data=$partes[2].'/'.$partes[1].'/'.$partes[0];
	}
	
	public function obterDiaSemana($data,$curto=true){
		if(!$data){
			return;
		}
		
		$dia=$this->obterDiaIngles($data);
		
		if($dia=='Sunday'){
			$dia='Domingo';
		}else if($dia=='Monday'){
			$dia='Segunda';
		}else if($dia=='Tuesday'){
			$dia='Terça';
		}else if($dia=='Wednesday'){
			$dia='Quarta';
		}else if($dia=='Thursday'){
			$dia='Quinta';
		}else if($dia=='Friday'){
			$dia='Sexta';
		}else if($dia=='Saturday'){
			$dia='Sábado';
		}
		
		if($curto){
			$dia=substr($dia,0,3);
		}
		
		return $dia;
	}
	
	private function obterDiaIngles($data){
		$partes=explode('-',$data);
		$data=date_create();
		date_date_set($data,$partes[0],$partes[1],$partes[2]);
		return date_format($data,'l');
	}
	
	public function obterMes(&$mes){
		if(!$mes){
			return;
		}
		
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
}