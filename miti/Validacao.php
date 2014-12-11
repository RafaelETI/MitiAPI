<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Valida��o de dados
 */
class Validacao{
	/**
	 * Valida a quantidade de caract�res de um valor
	 * 
	 * @api
	 * @param mixed $valor
	 * @param int $tamanho
	 * @return null
	 * @throws \UnexpectedValueException
	 */
	public static function tamanho($valor, $tamanho){
		if(!$valor){return;}
		
		if(strlen($valor) !== $tamanho){
			throw new \UnexpectedValueException("O valor deve conter at� $tamanho caract�res.");
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
	 * @throws \UnexpectedValueException
	 */
	public static function email($valor){
		if(!$valor){return;}
		
		if(!preg_match('/^\w{2,}@\w{2,}\.(\w|\.){2,}$/', $valor)){
			throw new \UnexpectedValueException('O e-mail � inv�lido.');
		}
	}
	
	/**
	 * Valida o peso de um arquivo
	 * 
	 * @param int $real
	 * @param int $esperado
	 * @throws \UnexpectedValueException
	 */
	public static function peso($real, $esperado){
		if($real > $esperado * 1024){
			throw new \UnexpectedValueException('O arquivo excede o peso permitido.');
		}
	}
	
	/**
	 * Valida o tipo do arquivo
	 * 
	 * @param string $real
	 * @param string[] $esperados
	 * @throws \RangeException
	 */
	public static function tipos($real, array $esperados){
		$ok = false;
		
		foreach($esperados as $esperado){
			if(strpos($real, $esperado) !== false){
				$ok = true;
			}
		}
		
		if(!$ok){throw new \RangeException('O tipo do arquivo � inv�lido.');}
	}
	
	/**
	 * Valida uma imagem
	 * 
	 * @api
	 * @param string|resource $imagem
	 * @param int $largura Em pixels.
	 * @param int $altura Em pixels.
	 */
	public static function imagem($imagem, $largura, $altura){
		$dimensoes = is_string($imagem)? getimagesize($imagem): array(imagesx($imagem), imagesy($imagem));
		
		self::dimensoes($dimensoes, $largura, $altura);
		self::proporcoes($dimensoes, $largura, $altura);
	}
	
	/**
	 * Valida as dimens�es de uma imagem
	 * 
	 * @param int[] $dimensoes
	 * @param int $largura
	 * @param int $altura
	 * @throws \UnexpectedValueException
	 */
	private static function dimensoes($dimensoes, $largura, $altura){
		if($dimensoes[0] < $largura){
			throw new \UnexpectedValueException('A largura da imagem � menor do que o m�nimo permitido.');
		}
		
		if($dimensoes[1] < $altura){
			throw new \UnexpectedValueException('A altura da imagem � menor do que o m�nimo permitido.');
		}
	}
	
	/**
	 * Valida as propor��es de uma imagem
	 * 
	 * @param int[] $dimensoes
	 * @param int $largura
	 * @param int $altura
	 * @throws \UnexpectedValueException
	 */
	private static function proporcoes($dimensoes, $largura, $altura){
		$proporcaoIdeal = $largura / $altura;
		$proporcaoMinima = $proporcaoIdeal - 0.1;
		$proporcaoMaxima = $proporcaoIdeal + 0.1;
		$proporcaoDaImagem = $dimensoes[0] / $dimensoes[1];
		
		if($proporcaoDaImagem < $proporcaoMinima){
			throw new \UnexpectedValueException('A propor��o da imagem � inv�lida, excedendo verticalmente.');
		}
		
		if($proporcaoDaImagem > $proporcaoMaxima){
			throw new \UnexpectedValueException('A propor��o da imagem � inv�lida, excedendo horizontalmente.');
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
		if(!$cpf){return;}
		
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
	 * @throws \UnexpectedValueException
	 */
	private static function quantidadeCaracteres($cpf){
		if(strlen($cpf) !== 11){throw new \UnexpectedValueException('#1 O CPF � inv�lido.');}
	}
	
	/**
	 * Valida se apenas possui n�meros
	 * 
	 * @param string $cpf
	 * @throws \UnexpectedValueException
	 */
	private static function apenasNumeros($cpf){
		if(!preg_match('/\d{11}/', $cpf)){throw new \UnexpectedValueException('#2 O CPF � inv�lido.');}
	}
	
	/**
	 * Valida se � uma sequ�ncia de n�meros repetidos
	 * 
	 * Por incr�vel que pare�a, repeti��es de sequ�ncias de um � nove satisfazem
	 * os c�lculos dos d�gitos verificadores.
	 * 
	 * @param string $cpf
	 * @throws \UnexpectedValueException
	 */
	private static function sequenciaIgual($cpf){
		for($posicao = 1, $numero = $cpf[0]; $posicao <= 10; $posicao++){
			if($numero != $cpf[$posicao]){break;}
			if($posicao == 10){throw new \UnexpectedValueException('#3 O CPF � inv�lido.');}
		}
	}
	
	/**
	 * Valida os d�gitos verificadores
	 * 
	 * @param string $cpf
	 * @throws \RangeException
	 */
	private static function digitosCpf($cpf){
		for($posicao = 9; $posicao <= 10; $posicao++){
			for($digito = 0, $numero = 0; $numero < $posicao; $numero++){
				$digito += $cpf[$numero] * (($posicao + 1) - $numero);
			}
			
			$digito = ((10 * $digito) % 11) % 10;
			if($cpf[$numero] != $digito){throw new \RangeException('#4 O CPF � inv�lido.');}
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
		if(!$cnpj){return;}
		
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
	 * @throws \UnexpectedValueException
	 */
	private static function quantidadeCaracteresCnpj($cnpj){
		if(strlen($cnpj) !== 14){throw new \UnexpectedValueException('#1 O CNPJ � inv�lido.');}
	}
	
	/**
	 * Valida se apenas possui n�meros
	 * 
	 * @param string $cnpj
	 * @throws \UnexpectedValueException
	 */
	private static function apenasNumerosCnpj($cnpj){
		if(!preg_match('/\d{14}/', $cnpj)){throw new \UnexpectedValueException('#2 O CNPJ � inv�lido.');}
	}
	
	/**
	 * Valida se � uma sequ�ncia de zeros
	 * 
	 * Diferente do CPF, essa � a �nica sequ�ncia num�rica problem�tica.
	 * 
	 * @param string $cnpj
	 * @throws \UnexpectedValueException
	 */
	private static function sequenciaZeros($cnpj){
		if($cnpj == '00000000000000'){throw new \UnexpectedValueException('#3 O CNPJ � inv�lido.');}
	}
	
	/**
	 * Valida os d�gitos verificadores
	 * 
	 * @param string $cnpj
	 * @throws \RangeException
	 */
	private static function digitosCnpj($cnpj){
		for($i = 0; $i <= 1; $i++){
			for($numero = 0, $j = 5 + $i, $soma = 0; $numero <= 11 + $i; $numero++){
				if($numero === 4 + $i){$j = 9;}
				$soma += $cnpj[$numero] * $j--;
			}
			
			$resto = $soma % 11;
			$digito = $resto < 2? 0: 11 - $resto;
			if($cnpj[12 + $i] != $digito){throw new \RangeException('#4 O CNPJ � inv�lido.');}
		}
	}
}
