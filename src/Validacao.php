<?php
namespace Miti;

class Validacao{
	public static function tamanho($valor, $tamanho){
		if(!$valor){return;}
		
		if(mb_strlen($valor) !== $tamanho){
			throw new \UnexpectedValueException("O valor deve conter até $tamanho caractéres.");
		}
	}
	
	public static function email($valor){
		if(!$valor){return;}
		
		if(!preg_match('/^\w{2,}@\w{2,}\.(\w|\.){2,}$/', $valor)){
			throw new \UnexpectedValueException('O e-mail é inválido.');
		}
	}
	
	public static function peso($real, $esperado){
		if($real > $esperado * 1024){
			throw new \UnexpectedValueException('O arquivo excede o peso permitido.');
		}
	}
	
	public static function tipos($real, array $esperados){
		$ok = false;
		
		foreach($esperados as $esperado){
			if(mb_strpos($real, $esperado) !== false){$ok = true;}
		}
		
		if(!$ok){throw new \RangeException('O tipo do arquivo é inválido.');}
	}
	
	public static function imagem($imagem, $largura, $altura){
		$dimensoes = is_string($imagem)? getimagesize($imagem): array(imagesx($imagem), imagesy($imagem));
		
		self::dimensoes($dimensoes, $largura, $altura);
		self::proporcoes($dimensoes, $largura, $altura);
	}
	
	private static function dimensoes($dimensoes, $largura, $altura){
		if($dimensoes[0] < $largura){
			throw new \UnexpectedValueException('A largura da imagem é menor do que o mínimo permitido.');
		}
		
		if($dimensoes[1] < $altura){
			throw new \UnexpectedValueException('A altura da imagem é menor do que o mínimo permitido.');
		}
	}
	
	private static function proporcoes($dimensoes, $largura, $altura){
		$proporcaoIdeal = $largura / $altura;
		$proporcaoMinima = $proporcaoIdeal - 0.1;
		$proporcaoMaxima = $proporcaoIdeal + 0.1;
		$proporcaoDaImagem = $dimensoes[0] / $dimensoes[1];
		
		if($proporcaoDaImagem < $proporcaoMinima){
			throw new \UnexpectedValueException('A proporção da imagem é inválida, excedendo verticalmente.');
		}
		
		if($proporcaoDaImagem > $proporcaoMaxima){
			throw new \UnexpectedValueException('A proporção da imagem é inválida, excedendo horizontalmente.');
		}
	}
	
	public static function cpf($cpf){
		if(!$cpf){return;}
		
		self::quantidadeCaracteres($cpf);
		self::apenasNumeros($cpf);
		self::sequenciaIgual($cpf);
		self::digitosCpf($cpf);
	}
	
	private static function quantidadeCaracteres($cpf){
		if(mb_strlen($cpf) !== 11){throw new \UnexpectedValueException('#1 O CPF é inválido.');}
	}
	
	private static function apenasNumeros($cpf){
		if(!preg_match('/\d{11}/', $cpf)){throw new \UnexpectedValueException('#2 O CPF é inválido.');}
	}
	
	private static function sequenciaIgual($cpf){
		for($posicao = 1, $numero = $cpf[0]; $posicao <= 10; $posicao++){
			if($numero != $cpf[$posicao]){break;}
			if($posicao == 10){throw new \UnexpectedValueException('#3 O CPF é inválido.');}
		}
	}
	
	private static function digitosCpf($cpf){
		for($posicao = 9; $posicao <= 10; $posicao++){
			for($digito = 0, $numero = 0; $numero < $posicao; $numero++){
				$digito += $cpf[$numero] * (($posicao + 1) - $numero);
			}
			
			$digito = ((10 * $digito) % 11) % 10;
			if($cpf[$numero] != $digito){throw new \RangeException('#4 O CPF é inválido.');}
		}
	}
	
	public static function cnpj($cnpj){
		if(!$cnpj){return;}
		
		self::quantidadeCaracteresCnpj($cnpj);
		self::apenasNumerosCnpj($cnpj);
		self::sequenciaZeros($cnpj);
		self::digitosCnpj($cnpj);
	}
	
	private static function quantidadeCaracteresCnpj($cnpj){
		if(mb_strlen($cnpj) !== 14){throw new \UnexpectedValueException('#1 O CNPJ é inválido.');}
	}
	
	private static function apenasNumerosCnpj($cnpj){
		if(!preg_match('/\d{14}/', $cnpj)){throw new \UnexpectedValueException('#2 O CNPJ é inválido.');}
	}
	
	private static function sequenciaZeros($cnpj){
		if($cnpj == '00000000000000'){throw new \UnexpectedValueException('#3 O CNPJ é inválido.');}
	}
	
	private static function digitosCnpj($cnpj){
		for($i = 0; $i <= 1; $i++){
			for($numero = 0, $j = 5 + $i, $soma = 0; $numero <= 11 + $i; $numero++){
				if($numero === 4 + $i){$j = 9;}
				$soma += $cnpj[$numero] * $j--;
			}
			
			$resto = $soma % 11;
			$digito = $resto < 2? 0: 11 - $resto;
			if($cnpj[12 + $i] != $digito){throw new \RangeException('#4 O CNPJ é inválido.');}
		}
	}
}
