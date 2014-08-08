<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Operação sobre data
 * 
 * Dois motivos importantes: oferece suporte para datas no formato brasileiro, e
 * não assume a data atual na ausência de uma outra na construção do objeto;
 * diferente da classe nativa DateTime.
 */
class Data{
	/**
	 * Inverte uma data no formato brasileiro para o formato norte americano
	 * 
	 * Basta trocar a barra pelo hífen que a classe DateTime reconhece como data,
	 * ou seja, não precisa estar na ordem do formato americano. Até porque, se
	 * precisasse, não haveria sentido em usá-la.
	 * 
	 * @api
	 * @param string $data
	 * @param bool $longo Se for true, retorna-se também a hora (timestamp).
	 * @return string|null
	 */
	public static function inverterBrParaEua($data,$longo=false){
		if(!$data){return;}
		
		$data=str_replace('/','-',$data);
		
		$DateTime=new \DateTime($data);
		$data=$DateTime->format('Y-m-d H:i:s');
		
		if(!$longo){$data=substr($data,0,10);}
		
		return $data;
	}
	
	/**
	 * Inverte um data no formato norte americano para o formato brasileiro
	 * 
	 * @api
	 * @param string $data
	 * @param bool $longo Se for true, retorna-se também a hora (timestamp).
	 * @return string|null
	 */
	public static function inverterEuaParaBr($data,$longo=false){
		if(!$data){return;}
		
		$DateTime=new \DateTime($data);
		$data=$DateTime->format('d/m/Y H:i:s');
		
		if(!$longo){$data=substr($data,0,10);}
		
		return $data;
	}
	
	/**
	 * Obtém o dia da semana à partir de uma data, em forma de texto
	 * 
	 * É um trabalho majoritariamente de tradução.
	 * 
	 * @api
	 * @param string $data
	 * @param bool $longo Se for false, retorna apenas as três primeiras letras.
	 * @return string|null
	 */
	public static function obterDiaDaSemana($data,$longo=false){
		if(!$data){return;}
		
		$DateTime=new \DateTime($data);
		$dia=$DateTime->format('l');
		
		switch($dia){
			case 'Sunday':$dia='Domingo'; break;
			case 'Monday':$dia='Segunda'; break;
			case 'Tuesday':$dia='Terça'; break;
			case 'Wednesday':$dia='Quarta'; break;
			case 'Thursday':$dia='Quinta'; break;
			case 'Friday':$dia='Sexta'; break;
			case 'Saturday':$dia='Sábado'; break;
		}
		
		if(!$longo){$dia=substr($dia,0,3);}
		
		return $dia;
	}
	
	/**
	 * Obtém o mês à partir de uma data, em forma de texto
	 * 
	 * @api
	 * @param string $data
	 * @param bool $longo Se for false, retorna apenas as três primeiras letras.
	 * @return string|null
	 */
	public static function obterMes($data,$longo=false){
		if(!$data){return;}
		
		$DateTime=new \DateTime($data);
		$mes=$DateTime->format('m');
		
		switch($mes){
			case '01':$mes='Janeiro'; break;
			case '02':$mes='Fevereiro'; break;
			case '03':$mes='Março'; break;
			case '04':$mes='Abril'; break;
			case '05':$mes='Maio'; break;
			case '06':$mes='Junho'; break;
			case '07':$mes='Julho'; break;
			case '08':$mes='Agosto'; break;
			case '09':$mes='Setembro'; break;
			case '10':$mes='Outubro'; break;
			case '11':$mes='Novembro'; break;
			case '12':$mes='Dezembro'; break;
		}
		
		if(!$longo){$mes=substr($mes,0,3).'.';}
		
		return $mes;
	}
	
	/**
	 * Obtém o ano à partir de uma data
	 * 
	 * @api
	 * @param string $data
	 * @return string|null
	 */
	public static function obterAno($data){
		if(!$data){return;}
		$DateTime=new \DateTime($data);
		return $DateTime->format('Y');
	}
}
