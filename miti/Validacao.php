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
		is_array($valores)? self::vazioArray($valores): self::vazioScalar($valores);
	}
	
	private static function vazioArray(array $valores){
		foreach($valores as $valor){self::vazioScalar($valor);}
	}
	
	private static function vazioScalar($valor){
		if(!$valor){throw new \UnexpectedValueException('Valor vazio.');}
	}
	
	/**
	 * Valida um arquivo
	 * 
	 * @api
	 * @param array[] $arquivos No mesmo formato de $_FILES['...'] m�ltiplo.
	 * @param int $peso Em kylobytes.
	 * 
	 * @param string[] $tipos Peda�os de strings pertencentes aos mime types
	 * desejados.
	 */
	public static function arquivo($arquivos, $peso, array $tipos){
		foreach($arquivos['name'] as $i => $nome){
			if(!$nome){break;}
			
			self::peso($arquivos, $i, $peso);
			self::tipos($arquivos, $i, $tipos);
		}
	}
	
	/**
	 * Valida o peso de um arquivo
	 * 
	 * @param array[] $arquivos
	 * @param int $i �ndice do vetor dos arquivos.
	 * @param int $peso
	 * @throws \UnexpectedValueException
	 */
	private static function peso($arquivos, $i, $peso){
		if($arquivos['size'][$i] > $peso * 1024){
			throw new \UnexpectedValueException('O arquivo excede o tamanho permitido.');
		}
	}
	
	/**
	 * Valida o tipo do arquivo
	 * 
	 * @param array[] $arquivos
	 * @param int $i �ndice do vetor dos arquivos.
	 * @param string[] $tipos
	 * @throws \RangeException
	 */
	private static function tipos($arquivos, $i, array $tipos){
		$ok = false;
		
		foreach($tipos as $tipo){
			if(strpos($arquivos['type'][$i], $tipo) !== false){
				$ok = true;
			}
		}
		
		if(!$ok){throw new \RangeException('O tipo do arquivo � inv�lido.');}
	}
	
	/**
	 * Valida uma imagem
	 * 
	 * @api
	 * @param array[] $arquivos No mesmo formato de $_FILES['...'] m�ltiplo.
	 * @param int $largura Em pixels.
	 * @param int $altura Em pixels.
	 */
	public static function imagem($arquivos, $largura, $altura){
		foreach($arquivos['name'] as $i => $nome){
			if(!$nome){break;}
			
			$dimensoes = getimagesize($arquivos['tmp_name'][$i]);
			self::dimensoes($dimensoes, $largura, $altura);
			self::proporcoes($dimensoes, $largura, $altura);
		}
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
