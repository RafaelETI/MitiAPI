<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Valida��o de dados
 */
class MitiValidacao{
	/**
	 * Valida a quantidade de caract�res de um valor
	 * 
	 * @api
	 * @param mixed $valor
	 * @param int $tamanho
	 * @return null
	 * @throws \Exception
	 */
	public static function tamanho($valor,$tamanho){
		if(!$valor){
			return;
		}
		
		if(strlen($valor)!=$tamanho){
			throw new Exception('O valor deve conter at� '.$tamanho.' caract�res.');
		}
	}
	
	/**
	 * Valida o formato de um e-mail
	 * 
	 * O formato deve ser algo parecido com: aa(at)aa.aa
	 * 
	 * @api
	 * @param string $valor
	 * @return null
	 * @throws \Exception
	 */
	public static function email($valor){
		if(!$valor){
			return;
		}
		
		if(!preg_match('/^\w{2,}@\w{2,}\.(\w|\.){2,}$/',$valor)){
			throw new Exception('O e-mail � inv�lido.');
		}
	}
	
	/**
	 * Valida se um valor ou valores n�o s�o equivalentes � vazio
	 * 
	 * Diferente da maioria dos m�todos, n�o retorna null se o valor for
	 * equivalente � false porque sua pr�pria valida��o j� verifica se o valor
	 * � equivalente � vazio.
	 * 
	 * @api
	 * @param mixed|mixed[] $valores
	 */
	public static function vazio($valores){
		if(is_array($valores)){
			self::vazioArray($valores);
		}else{
			self::vazioScalar($valores);
		}
	}
	
	/**
	 * Valida se algum valor de um vetor � equivalente � vazio
	 * 
	 * @param mixed[] $valores
	 * @throws \Exception
	 */
	private static function vazioArray(array $valores){
		foreach($valores as $v){
			if(!$v){
				throw new Exception('Valor vazio.');
			}
		}
	}
	
	/**
	 * Valida se um valor � equivalente � vazio
	 * 
	 * @param mixed $valor
	 * @throws \Exception
	 */
	private static function vazioScalar($valor){
		if(!$valor){
			throw new Exception('Valor vazio.');
		}
	}
	
	/**
	 * Valida um arquivo em upload
	 * 
	 * O atributo name da tag input file do formul�rio HTML deve ser concatenado
	 * � [] (colchetes), mesmo que o upload n�o seja m�ltiplo, porque o programa
	 * manuseia uma estrutura de dados igual � que seria se o upload fosse
	 * m�ltiplo.
	 * 
	 * @api
	 * @param string $file Name do input file sem colchetes.
	 * @param int $peso Em kylobytes.
	 * 
	 * @param string[] $tipos Peda�os de strings pertencentes aos mime types
	 * desejados
	 */
	public static function upload($file,$peso,array $tipos){
		foreach($_FILES[$file]['name'] as $i=>$v){
			if(!$v){
				break;
			}
			
			self::peso($file,$i,$peso);
			self::tipos($file,$i,$tipos);
		}
	}
	
	/**
	 * Valida o peso de um arquivo
	 * 
	 * @param string $file
	 * @param int $i �ndice do vetor do upload
	 * @param int $peso
	 * @throws \Exception
	 */
	private static function peso($file,$i,$peso){
		$peso*=1024;
		
		if($_FILES[$file]['size'][$i]>$peso){
			throw new Exception('O arquivo excede o tamanho permitido.');
		}
	}
	
	/**
	 * Valida o tipo do arquivo
	 * 
	 * @param string $file
	 * @param int $i �ndice do vetor do upload
	 * @param string[] $tipos
	 * @throws \Exception
	 */
	private static function tipos($file,$i,array $tipos){
		$ok=false;
		
		foreach($tipos as $v){
			if(strpos($_FILES[$file]['type'][$i],$v)!==false){
				$ok=true;
			}
		}
		
		if(!$ok){
			throw new Exception('O tipo do arquivo � inv�lido.');
		}
	}
	
	/**
	 * Valida uma imagem em upload
	 * 
	 * O atributo name da tag input file do formul�rio HTML deve ser concatenado
	 * � [] (colchetes), mesmo que o upload n�o seja m�ltiplo, porque o programa
	 * manuseia uma estrutura de dados igual � que seria se o upload fosse
	 * m�ltiplo.
	 * 
	 * @api
	 * @param string $file Name do input file sem colchetes.
	 * @param int $largura Em pixels.
	 * @param int $altura Em pixels.
	 */
	public static function uploadImagem($file,$largura,$altura){
		foreach($_FILES[$file]['name'] as $i=>$v){
			if(!$v){
				break;
			}
			
			$dimensoes=getimagesize($_FILES[$file]['tmp_name'][$i]);
			self::dimensoes($dimensoes,$largura,$altura);
			self::proporcoes($dimensoes,$largura,$altura);
		}
	}
	
	/**
	 * Valida as dimens�es de uma imagem
	 * 
	 * @param int[] $dimensoes
	 * @param int $largura
	 * @param int $altura
	 * @throws \Exception
	 */
	private static function dimensoes($dimensoes,$largura,$altura){
		if($dimensoes[0]<$largura){
			throw new Exception(
				'A largura da imagem � menor do que o m�nimo permitido.'
			);
		}
		
		if($dimensoes[1]<$altura){
			throw new Exception(
				'A altura da imagem � menor do que o m�nimo permitido.'
			);
		}
	}
	
	/**
	 * Valida as propor��es de uma imagem
	 * 
	 * @param int[] $dimensoes
	 * @param int $largura
	 * @param int $altura
	 * @throws \Exception
	 */
	private static function proporcoes($dimensoes,$largura,$altura){
		$prop_args=$largura/$altura;
		$prop_min=$prop_args-0.1;
		$prop_max=$prop_args+0.1;
		$prop_img=$dimensoes[0]/$dimensoes[1];
		
		if($prop_img<$prop_min){
			throw new Exception(
				'A propor��o da imagem � inv�lida, excedendo verticalmente.'
			);
		}
		
		if($prop_img>$prop_max){
			throw new Exception(
				'A propor��o da imagem � inv�lida, excedendo horizontalmente.'
			);
		}
	}
	
	/**
	 * Valida um CPF
	 * 
	 * @api
	 * @param string $cpf
	 * @return null
	 */
	public static function cpf($cpf){
		if(!$cpf){
			return;
		}
		
		self::quantidadeCaracteres($cpf);
		self::apenasNumeros($cpf);
		self::sequenciaIgual($cpf);
		self::digitosCpf($cpf);
	}
	
	/**
	 * Valida a quantidade de caract�res
	 * 
	 * A numera��o na mensagem de exce��o � para que o desenvolvedor consiga
	 * localizar o c�digo que lan�ou a exce��o sem que o usu�rio tenha uma
	 * mensagem expl�cita do erro, para que haja uma menor chance de burlamento.
	 * 
	 * @param string $cpf
	 * @throws \Exception
	 */
	private static function quantidadeCaracteres($cpf){
		if(strlen($cpf)!==11){
			throw new Exception('#1 O CPF � inv�lido.');
		}
	}
	
	/**
	 * Valida se apenas possui n�meros
	 * 
	 * @param string $cpf
	 * @throws \Exception
	 */
	private static function apenasNumeros($cpf){
		if(!preg_match('/\d{11}/',$cpf)){
			throw new Exception('#2 O CPF � inv�lido.');
		}
	}
	
	/**
	 * Valida se � uma sequ�ncia de n�meros repetidos
	 * 
	 * Por incr�vel que pare�a, repeti��es de sequ�ncias de um � nove satisfazem
	 * os c�lculos dos d�gitos verificadores.
	 * 
	 * @param string $cpf
	 * @throws \Exception
	 */
	private static function sequenciaIgual($cpf){
		for($i=1,$j=$cpf[0];$i<=10;$i++){
			if($j!=$cpf[$i]){
				break;
			}
			
			if($i==10){
				throw new Exception('#3 O CPF � inv�lido.');
			}
		}
	}
	
	/**
	 * Valida os d�gitos verificadores
	 * 
	 * @param string $cpf
	 * @throws \Exception
	 */
	private static function digitosCpf($cpf){
		for($i=9;$i<=10;$i++){
			for($digito=0,$numero=0;$numero<$i;$numero++){
				$digito+=$cpf[$numero]*(($i+1)-$numero);
			}
			
			$digito=((10*$digito)%11)%10;
			
			if($cpf[$numero]!=$digito){
				throw new Exception('#4 O CPF � inv�lido.');
			}
		}
	}
	
	/**
	 * Valida um CNPJ
	 * 
	 * @api
	 * @param string $cnpj
	 * @return null
	 */
	public static function cnpj($cnpj){
		if(!$cnpj){
			return;
		}
		
		self::quantidadeCaracteresCnpj($cnpj);
		self::apenasNumerosCnpj($cnpj);
		self::sequenciaZeros($cnpj);
		self::digitosCnpj($cnpj);
	}
	
	/**
	 * Valida a quantidade de caract�res
	 * 
	 * A numera��o na mensagem de exce��o � para que o desenvolvedor consiga
	 * localizar o c�digo que lan�ou a exce��o sem que o usu�rio tenha uma
	 * mensagem expl�cita do erro, para que haja uma menor chance de burlamento.
	 * 
	 * @param string $cnpj
	 * @throws \Exception
	 */
	private static function quantidadeCaracteresCnpj($cnpj){
		if(strlen($cnpj)!==14){
			throw new Exception('#1 O CNPJ � inv�lido.');
		}
	}
	
	/**
	 * Valida se apenas possui n�meros
	 * 
	 * @param string $cnpj
	 * @throws \Exception
	 */
	private static function apenasNumerosCnpj($cnpj){
		if(!preg_match('/\d{14}/',$cnpj)){
			throw new Exception('#2 O CNPJ � inv�lido.');
		}
	}
	
	/**
	 * Valida se � uma sequ�ncia de zeros
	 * 
	 * Diferente do CPF, essa � a �nica sequ�ncia num�rica problem�tica.
	 * 
	 * @param string $cnpj
	 * @throws \Exception
	 */
	private static function sequenciaZeros($cnpj){
		if($cnpj=='00000000000000'){
			throw new Exception('#3 O CNPJ � inv�lido.');
		}
	}
	
	/**
	 * Valida os d�gitos verificadores
	 * 
	 * @param string $cnpj
	 * @throws \Exception
	 */
	private static function digitosCnpj($cnpj){
		for($i=0;$i<=1;$i++){
			for($numero=0,$x=5+$i,$soma=0;$numero<=11+$i;$numero++){
				if($numero===4+$i){
					$x=9;
				}
				
				$soma+=$cnpj[$numero]*$x--;
			}
			
			$resto=$soma%11;
			
			if($resto<2){
				$digito=0;
			}else{
				$digito=11-$resto;
			}
			
			if($cnpj[12+$i]!=$digito){
				throw new Exception('#4 O CNPJ � inv�lido.');
			}
		}
	}
}
