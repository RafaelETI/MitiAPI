<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Operação sobre tempo
 * 
 * Dois motivos importantes: oferece suporte para datas no formato brasileiro, e
 * não assume a data atual na ausência de uma outra na construção do objeto;
 * diferente da classe nativa DateTime.
 */
class Tempo{
	/**
	 * Inverte um tempo no formato brasileiro para o formato norte americano
	 * 
	 * Basta trocar a barra pelo hífen que a classe DateTime reconhece como data,
	 * ou seja, não precisa estar na ordem do formato americano. Até porque, se
	 * precisasse, não haveria sentido em usá-la.
	 * 
	 * @api
	 * @param string $tempo
	 * @param bool $longo Se for true, retorna-se também a hora (timestamp).
	 * @return string|null
	 */
	public static function brUS($tempo, $longo = false){
		if(!$tempo){return;}
		
		$tempo = str_replace('/', '-', $tempo);
		
		$DateTime = new \DateTime($tempo);
		$tempo = $DateTime->format('Y-m-d H:i:s');
		
		if(!$longo){$tempo = substr($tempo, 0, 10);}
		
		return $tempo;
	}
	
	/**
	 * Inverte um tempo no formato norte americano para o formato brasileiro
	 * 
	 * @api
	 * @param string $tempo
	 * @param bool $longo Se for true, retorna-se também a hora (timestamp).
	 * @return string|null
	 */
	public static function usBR($tempo, $longo = false){
		if(!$tempo){return;}
		
		$DateTime = new \DateTime($tempo);
		$tempo = $DateTime->format('d/m/Y H:i:s');
		
		if(!$longo){$tempo = substr($tempo, 0, 10);}
		
		return $tempo;
	}
	
	/**
	 * Obtém o dia da semana à partir de um tempo, em forma de texto
	 * 
	 * É um trabalho majoritariamente de tradução.
	 * 
	 * @api
	 * @param string $tempo
	 * @param bool $longo Se for false, retorna apenas as três primeiras letras.
	 * @return string|null
	 */
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
		
		if(!$longo){$dia = substr($dia, 0, 3);}
		
		return $dia;
	}
	
	/**
	 * Obtém o mês à partir de um tempo, em forma de texto
	 * 
	 * @api
	 * @param string $tempo
	 * @param bool $longo Se for false, retorna apenas as três primeiras letras.
	 * @return string|null
	 */
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
		
		if(!$longo){$mes = substr($mes, 0, 3) . '.';}
		
		return $mes;
	}
	
	/**
	 * Obtém o ano à partir de um tempo
	 * 
	 * @api
	 * @param string $tempo
	 * @return string|null
	 */
	public static function ano($tempo){
		if(!$tempo){return;}
		$DateTime = new \DateTime($tempo);
		return $DateTime->format('Y');
	}
}
