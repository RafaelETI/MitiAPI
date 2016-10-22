<?php
namespace Miti;

class Tempo{
	public static function brUS($tempo, $longo = false){
		if(!$tempo){return;}
		
		$tempo = str_replace('/', '-', $tempo);
		
		$DateTime = new \DateTime($tempo);
		$tempo = $DateTime->format('Y-m-d H:i:s');
		
		if(!$longo){$tempo = mb_substr($tempo, 0, 10);}
		
		return $tempo;
	}
	
	public static function usBR($tempo, $longo = false){
		if(!$tempo){return;}
		
		$DateTime = new \DateTime($tempo);
		$tempo = $DateTime->format('d/m/Y H:i:s');
		
		if(!$longo){$tempo = mb_substr($tempo, 0, 10);}
		
		return $tempo;
	}
	
	public static function dia($tempo){
		if(!$tempo){return;}
		$DateTime = new \DateTime($tempo);
		return $DateTime->format('d');
	}
	
	public static function diaDaSemana($tempo, $longo = false){
		if(!$tempo){return;}
		
		$DateTime = new \DateTime($tempo);
		$dia = $DateTime->format('l');
		
		switch($dia){
			case 'Sunday': $dia = 'Domingo'; break;
			case 'Monday': $dia = 'Segunda'; break;
			case 'Tuesday': $dia = 'Terça'; break;
			case 'Wednesday': $dia = 'Quarta'; break;
			case 'Thursday': $dia = 'Quinta'; break;
			case 'Friday': $dia = 'Sexta'; break;
			case 'Saturday': $dia = 'Sábado'; break;
		}
		
		if(!$longo){$dia = mb_substr($dia, 0, 3);}
		
		return $dia;
	}
	
	public static function mes($tempo, $longo = false){
		if(!$tempo){return;}
		
		$DateTime = new \DateTime($tempo);
		$mes = $DateTime->format('m');
		
		switch($mes){
			case '01': $mes = 'Janeiro'; break;
			case '02': $mes = 'Fevereiro'; break;
			case '03': $mes = 'Março'; break;
			case '04': $mes = 'Abril'; break;
			case '05': $mes = 'Maio'; break;
			case '06': $mes = 'Junho'; break;
			case '07': $mes = 'Julho'; break;
			case '08': $mes = 'Agosto'; break;
			case '09': $mes = 'Setembro'; break;
			case '10': $mes = 'Outubro'; break;
			case '11': $mes = 'Novembro'; break;
			case '12': $mes = 'Dezembro'; break;
		}
		
		if(!$longo){$mes = mb_substr($mes, 0, 3) . '.';}
		
		return $mes;
	}
	
	public static function ano($tempo){
		if(!$tempo){return;}
		$DateTime = new \DateTime($tempo);
		return $DateTime->format('Y');
	}
	
	public static function somar(){
		$DateTime = new \DateTime('00:00:00');
		$DateTime2 = new \DateTime('00:00:00');
		
		foreach(func_get_args() as $Intervalo){$DateTime->add($Intervalo);}
		
		return $DateTime->diff($DateTime2, true);
	}
	
	public static function subtrair(){
		$parametros = func_get_args();
		$DateTime = new \DateTime(array_shift($parametros)->format('%H:%I:%S'));
		$DateTime2 = new \DateTime('00:00:00');
		
		foreach($parametros as $Intervalo){$DateTime->sub($Intervalo);}
		
		return $DateTime->diff($DateTime2, true);
	}
}
