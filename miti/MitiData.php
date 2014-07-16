<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Pacote de opera��es sobre data
 * 
 * Dois motivos importantes: oferece suporte para datas no formato brasileiro, e
 * n�o assume a data atual na aus�ncia de uma outra na constru��o do objeto;
 * diferente da classe nativa DateTime.
 */
class MitiData{
	/**
	 * Inverte uma data no formato brasileiro para o formato norte americano
	 * 
	 * Basta trocar a barra pelo h�fen que a classe DateTime reconhece como data,
	 * ou seja, n�o precisa estar na ordem do formato americano. At� porque, se
	 * precisasse, n�o haveria sentido em us�-la.
	 * 
	 * @api
	 * @param string $data
	 * @param bool $longo Se for true, retorna-se tamb�m a hora (timestamp).
	 * @return string|null
	 */
	public static function inverterBrParaEua($data,$longo=false){
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
	
	/**
	 * Inverte um data no formato norte americano para o formato brasileiro
	 * 
	 * @api
	 * @param string $data
	 * @param bool $longo Se for true, retorna-se tamb�m a hora (timestamp).
	 * @return string|null
	 */
	public static function inverterEuaParaBr($data,$longo=false){
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
	
	/**
	 * Obt�m o dia da semana � partir de uma data, em forma de texto
	 * 
	 * � um trabalho majoritariamente de tradu��o.
	 * 
	 * @api
	 * @param string $data
	 * @param bool $longo Se for false, retorna apenas as tr�s primeiras letras.
	 * @return string|null
	 */
	public static function obterDiaDaSemana($data,$longo=false){
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
			$dia='Ter�a';
		}else if($dia=='Wednesday'){
			$dia='Quarta';
		}else if($dia=='Thursday'){
			$dia='Quinta';
		}else if($dia=='Friday'){
			$dia='Sexta';
		}else if($dia=='Saturday'){
			$dia='S�bado';
		}
		
		if(!$longo){
			$dia=substr($dia,0,3);
		}
		
		return $dia;
	}
	
	/**
	 * Obt�m o m�s � partir de uma data, em forma de texto
	 * 
	 * @api
	 * @param string $data
	 * @param bool $longo Se for false, retorna apenas as tr�s primeiras letras.
	 * @return string|null
	 */
	public static function obterMes($data,$longo=false){
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
			$mes='Mar�o';
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
	
	/**
	 * Obt�m o ano � partir de uma data
	 * 
	 * @api
	 * @param string $data
	 * @return string|null
	 */
	public static function obterAno($data){
		if(!$data){
			return;
		}
		
		$DateTime=new DateTime($data);
		return $DateTime->format('Y');
	}
}
