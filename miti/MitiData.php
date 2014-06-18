<?php
/**
 * MitiAPI, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 */
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
	
	public function obterMes($data,$longo=false){
		if(!$data){
			return;
		}
		
		$DateTime=new DateTime($data);
		$mes=$DateTime->format('m');
		
		if($mes=='01'){
			$mes='Janeiro';
		}else if($mes=='02'){
			$mes='Fevereiro';
		}else if($mes=='03'){
			$mes='Março';
		}else if($mes=='04'){
			$mes='Abril';
		}else if($mes=='05'){
			$mes='Maio';
		}else if($mes=='06'){
			$mes='Junho';
		}else if($mes=='07'){
			$mes='Julho';
		}else if($mes=='08'){
			$mes='Agosto';
		}else if($mes=='09'){
			$mes='Setembro';
		}else if($mes=='10'){
			$mes='Outubro';
		}else if($mes=='11'){
			$mes='Novembro';
		}else if($mes=='12'){
			$mes='Dezembro';
		}
		
		if(!$longo){
			$mes=substr($mes,0,3).'.';
		}
		
		return $mes;
	}
	
	public function obterAno($data){
		if(!$data){
			return;
		}
		
		$DateTime=new DateTime($data);
		return $DateTime->format('Y');
	}
}
