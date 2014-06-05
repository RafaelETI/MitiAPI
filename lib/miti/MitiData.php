<?php
class MitiData{
	public function inverterBrParaEua($data,$longo=false){
		if(!$data){
			return;
		}
		
		$data=str_replace('/','-',$data);
		
		$DateTime=new DateTime($data);
		$data=$DateTime->format('Y-m-d H:i:s');
		
		if(!$longo){
			$data=substr($data,0,10);
		}
		
		return $data;
	}
	
	public function inverterEuaParaBr($data,$longo=false){
		if(!$data){
			return;
		}
		
		$DateTime=new DateTime($data);
		$data=$DateTime->format('d/m/Y H:i:s');
		
		if(!$longo){
			$data=substr($data,0,10);
		}
		
		return $data;
	}
	
	public function obterDiaSemana($data,$longo=false){
		if(!$data){
			return;
		}
		
		$DateTime=new DateTime($data);
		$dia=$DateTime->format('l');
		
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
		
		if(!$longo){
			$dia=substr($dia,0,3);
		}
		
		return $dia;
	}
	
	public function obterMes($mes){
		if(!$mes){
			return;
		}
		
		if($mes=='01'){
			$dia='Janeiro';
		}else if($mes=='02'){
			$dia='Fevereiro';
		}else if($mes=='03'){
			$dia='Março';
		}else if($mes=='04'){
			$dia='Abril';
		}else if($mes=='05'){
			$dia='Maio';
		}else if($mes=='06'){
			$dia='Junho';
		}else if($mes=='07'){
			$dia='Julho';
		}else if($mes=='08'){
			$dia='Agosto';
		}else if($mes=='09'){
			$dia='Setembro';
		}else if($mes=='10'){
			$dia='Outubro';
		}else if($mes=='11'){
			$dia='Novembro';
		}else if($mes=='12'){
			$dia='Dezembro';
		}
		
		return $dia;
	}
}
