<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Opera��o sobre tempo
 * 
 * Dois motivos importantes: oferece suporte para datas no formato brasileiro, e
 * n�o assume a data atual na aus�ncia de uma outra na constru��o do objeto;
 * diferente da classe nativa DateTime.
 */
class Tempo{
	/**
	 * Inverte um tempo no formato brasileiro para o formato norte americano
	 * 
	 * Basta trocar a barra pelo h�fen que a classe DateTime reconhece como data,
	 * ou seja, n�o precisa estar na ordem do formato americano. At� porque, se
	 * precisasse, n�o haveria sentido em us�-la.
	 * 
	 * @api
	 * @param string $tempo
	 * @param bool $longo Se for true, retorna-se tamb�m a hora (timestamp).
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
	 * @param bool $longo Se for true, retorna-se tamb�m a hora (timestamp).
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
	 * Obt�m o dia da semana � partir de um tempo, em forma de texto
	 * 
	 * � um trabalho majoritariamente de tradu��o.
	 * 
	 * @api
	 * @param string $tempo
	 * @param bool $longo Se for false, retorna apenas as tr�s primeiras letras.
	 * @return string|null
	 */
	public static function diaDaSemana($tempo, $longo = false){
		if(!$tempo){return;}
		
		$DateTime = new \DateTime($tempo);
		$dia = $DateTime->format('l');
		
		switch($dia){
			case 'Sunday': $dia = 'Domingo'; break;
			case 'Monday': $dia = 'Segunda'; break;
			case 'Tuesday': $dia = 'Ter�a'; break;
			case 'Wednesday': $dia = 'Quarta'; break;
			case 'Thursday': $dia = 'Quinta'; break;
			case 'Friday': $dia = 'Sexta'; break;
			case 'Saturday': $dia = 'S�bado'; break;
		}
		
		if(!$longo){$dia = substr($dia, 0, 3);}
		
		return $dia;
	}
	
	/**
	 * Obt�m o m�s � partir de um tempo, em forma de texto
	 * 
	 * @api
	 * @param string $tempo
	 * @param bool $longo Se for false, retorna apenas as tr�s primeiras letras.
	 * @return string|null
	 */
	public static function mes($tempo, $longo = false){
		if(!$tempo){return;}
		
		$DateTime = new \DateTime($tempo);
		$mes = $DateTime->format('m');
		
		switch($mes){
			case '01': $mes = 'Janeiro'; break;
			case '02': $mes = 'Fevereiro'; break;
			case '03': $mes = 'Mar�o'; break;
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
	 * Obt�m o ano � partir de um tempo
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
