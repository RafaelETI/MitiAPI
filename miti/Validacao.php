<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Validação de dados
 */
class Validacao{
	/**
	 * Valida a quantidade de caractéres de um valor
	 * 
	 * @api
	 * @param mixed $valor
	 * @param int $tamanho
	 * @return null
	 * @throws \Exception
	 */
	public static function tamanho($valor, $tamanho){
		if(!$valor){return;}
		
		if(strlen($valor) != $tamanho){
			throw new \Exception("O valor deve conter até $tamanho caractéres.");
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
		if(!$valor){return;}
		
		if(!preg_match('/^\w{2,}@\w{2,}\.(\w|\.){2,}$/', $valor)){
			throw new \Exception('O e-mail é inválido.');
		}
	}
	
	/**
	 * Valida se um valor ou valores não são equivalentes à vazio
	 * 
	 * Diferente da maioria dos métodos, não retorna null se o valor for
	 * equivalente à false porque sua própria validação já verifica se o valor
	 * é equivalente à vazio.
	 * 
	 * @api
	 * @param mixed|mixed[] $valores
	 */
	public static function vazio($valores){
		is_array($valores)? self::vazioArray($valores): self::vazioScalar($valores);
	}
	
	private static function vazioArray(array $valores){
		foreach($valores as $valor){
			if(!$valor){throw new \Exception('Valor vazio.');}
		}
	}
	
	private static function vazioScalar($valor){
		if(!$valor){throw new \Exception('Valor vazio.');}
	}
	
	/**
	 * Valida um arquivo
	 * 
	 * @api
	 * @param array[] $arquivos No mesmo formato de $_FILES['...'] múltiplo.
	 * @param int $peso Em kylobytes.
	 * 
	 * @param string[] $tipos Pedaços de strings pertencentes aos mime types
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
	 * @param int $i Índice do vetor dos arquivos.
	 * @param int $peso
	 * @throws \Exception
	 */
	private static function peso($arquivos, $i, $peso){
		$peso *= 1024;
		
		if($arquivos['size'][$i] > $peso){
			throw new \Exception('O arquivo excede o tamanho permitido.');
		}
	}
	
	/**
	 * Valida o tipo do arquivo
	 * 
	 * @param array[] $arquivos
	 * @param int $i Índice do vetor dos arquivos.
	 * @param string[] $tipos
	 * @throws \Exception
	 */
	private static function tipos($arquivos, $i, array $tipos){
		$ok = false;
		
		foreach($tipos as $tipo){
			if(strpos($arquivos['type'][$i], $tipo) !== false){
				$ok = true;
			}
		}
		
		if(!$ok){throw new \Exception('O tipo do arquivo é inválido.');}
	}
	
	/**
	 * Valida uma imagem
	 * 
	 * @api
	 * @param array[] $arquivos No mesmo formato de $_FILES['...'] múltiplo.
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
	 * Valida as dimensões de uma imagem
	 * 
	 * @param int[] $dimensoes
	 * @param int $largura
	 * @param int $altura
	 * @throws \Exception
	 */
	private static function dimensoes($dimensoes, $largura, $altura){
		if($dimensoes[0] < $largura){
			throw new \Exception('A largura da imagem é menor do que o mínimo permitido.');
		}
		
		if($dimensoes[1] < $altura){
			throw new \Exception('A altura da imagem é menor do que o mínimo permitido.');
		}
	}
	
	/**
	 * Valida as proporções de uma imagem
	 * 
	 * @param int[] $dimensoes
	 * @param int $largura
	 * @param int $altura
	 * @throws \Exception
	 */
	private static function proporcoes($dimensoes, $largura, $altura){
		$proporcaoIdeal = $largura / $altura;
		$proporcaoMinima = $proporcaoIdeal - 0.1;
		$proporcaoMaxima = $proporcaoIdeal + 0.1;
		$proporcaoDaImagem = $dimensoes[0] / $dimensoes[1];
		
		if($proporcaoDaImagem < $proporcaoMinima){
			throw new \Exception('A proporção da imagem é inválida, excedendo verticalmente.');
		}
		
		if($proporcaoDaImagem > $proporcaoMaxima){
			throw new \Exception('A proporção da imagem é inválida, excedendo horizontalmente.');
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
	 * Valida a quantidade de caractéres
	 * 
	 * A numeração na mensagem de exceção é para que o desenvolvedor consiga
	 * localizar o código que lançou a exceção sem que o usuário tenha uma
	 * mensagem explícita do erro, para que haja uma menor chance de burlamento.
	 * 
	 * @param string $cpf
	 * @throws \Exception
	 */
	private static function quantidadeCaracteres($cpf){
		if(strlen($cpf) !== 11){throw new \Exception('#1 O CPF é inválido.');}
	}
	
	/**
	 * Valida se apenas possui números
	 * 
	 * @param string $cpf
	 * @throws \Exception
	 */
	private static function apenasNumeros($cpf){
		if(!preg_match('/\d{11}/', $cpf)){throw new \Exception('#2 O CPF é inválido.');}
	}
	
	/**
	 * Valida se é uma sequência de números repetidos
	 * 
	 * Por incrível que pareça, repetições de sequências de um à nove satisfazem
	 * os cálculos dos dígitos verificadores.
	 * 
	 * @param string $cpf
	 * @throws \Exception
	 */
	private static function sequenciaIgual($cpf){
		for($posicao = 1, $primeiroNumero = $cpf[0]; $posicao <= 10; $posicao++){
			if($primeiroNumero != $cpf[$posicao]){break;}
			if($posicao == 10){throw new \Exception('#3 O CPF é inválido.');}
		}
	}
	
	/**
	 * Valida os dígitos verificadores
	 * 
	 * @param string $cpf
	 * @throws \Exception
	 */
	private static function digitosCpf($cpf){
		for($posicao = 9; $posicao <= 10; $posicao++){
			for($digito = 0, $numero = 0; $numero < $posicao; $numero++){
				$digito += $cpf[$numero] * (($posicao + 1) - $numero);
			}
			
			$digito = ((10 * $digito) % 11) % 10;
			if($cpf[$numero] != $digito){throw new \Exception('#4 O CPF é inválido.');}
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
	 * Valida a quantidade de caractéres
	 * 
	 * A numeração na mensagem de exceção é para que o desenvolvedor consiga
	 * localizar o código que lançou a exceção sem que o usuário tenha uma
	 * mensagem explícita do erro, para que haja uma menor chance de burlamento.
	 * 
	 * @param string $cnpj
	 * @throws \Exception
	 */
	private static function quantidadeCaracteresCnpj($cnpj){
		if(strlen($cnpj) !== 14){throw new \Exception('#1 O CNPJ é inválido.');}
	}
	
	/**
	 * Valida se apenas possui números
	 * 
	 * @param string $cnpj
	 * @throws \Exception
	 */
	private static function apenasNumerosCnpj($cnpj){
		if(!preg_match('/\d{14}/', $cnpj)){throw new \Exception('#2 O CNPJ é inválido.');}
	}
	
	/**
	 * Valida se é uma sequência de zeros
	 * 
	 * Diferente do CPF, essa é a única sequência numérica problemática.
	 * 
	 * @param string $cnpj
	 * @throws \Exception
	 */
	private static function sequenciaZeros($cnpj){
		if($cnpj == '00000000000000'){throw new \Exception('#3 O CNPJ é inválido.');}
	}
	
	/**
	 * Valida os dígitos verificadores
	 * 
	 * @param string $cnpj
	 * @throws \Exception
	 */
	private static function digitosCnpj($cnpj){
		for($i = 0; $i <= 1; $i++){
			for($numero = 0, $x = 5 + $i, $soma = 0; $numero <= 11 + $i; $numero++){
				if($numero === 4 + $i){$x = 9;}
				$soma += $cnpj[$numero] * $x--;
			}
			
			$resto = $soma % 11;
			$digito = $resto < 2? 0: 11 - $resto;
			if($cnpj[12 + $i] != $digito){throw new \Exception('#4 O CNPJ é inválido.');}
		}
	}
}
