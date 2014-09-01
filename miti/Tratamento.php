<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Tratamento de dados
 */
class Tratamento{
	/**
	 * Cria o html para inclus�o de arquivos CSS e JS
	 * 
	 * A diferen�a de chamar diretamente � que esse m�todo parametriza o link
	 * com o hash do arquivo, fazendo com que sempre que haja uma altera��o
	 * no conte�do do arquivo, o navegador veja como um novo arquivo e n�o use
	 * o que j� est� em cache.
	 * 
	 * N�o � interessante de se usar com arquivos de terceiros, visto que esses,
	 * � princ�pio, n�o s�o alterados permanecendo com o mesmo nome.
	 * 
	 * @api
	 * @param string $caminho
	 * @return string
	 */
	public static function requerer($caminho){
		$hash=md5(file_get_contents($caminho));
		
		$partes=explode('.',$caminho);
		$extensao=end($partes);
		
		if($extensao==='js'){
			$html="<script src='$caminho?hash=$hash'></script>\n";
		}else if($extensao==='css'){
			$html="<link rel='stylesheet' href='$caminho?hash=$hash' />\n";
		}
		
		return $html;
	}
	
	/**
	 * Substitui um valor baseado em um valor condicional
	 * 
	 * @api
	 * @param mixed $valor
	 * @param mixed $condicao
	 * @param mixed $novo
	 * @return mixed
	 */
	public static function substituirValor($valor,$condicao,$novo){
		if($valor===$condicao){$valor=$novo;}
		return $valor;
	}
	
	/**
	 * Garante a exist�ncia de �ndices de um vetor
	 * 
	 * O valor com o qual os �ndices s�o inicializados � passado por par�metro.
	 * 
	 * @api
	 * @param mixed[] $vetor
	 * @param string[] $indices
	 * @param mixed $valor
	 * @return mixed[]
	 */
	public static function garantirIndices($vetor,array $indices,$valor=''){
		foreach($indices as $indice){
			if(!isset($vetor[$indice])){$vetor[$indice]=$valor;}
		}
		
		return $vetor;
	}
	
	/**
	 * Garante o conte�do do c�digo fonte de um arquivo
	 * 
	 * @api
	 * @param string $arquivo
	 * @param string $caminho
	 * @return string
	 */
	public static function garantirArquivo($arquivo,$caminho){
		if(!$arquivo){$arquivo=file_get_contents($caminho);}
		return $arquivo;
	}
	
	/**
	 * Extende a capacidade da fun��o nativa htmlspecialchars()
	 * 
	 * Faz com que ela aceite tamb�m vetores, que considere aspas, e que
	 * considere outra codifica��o por padr�o.
	 * 
	 * @api
	 * @param string|string[] $valores
	 * @param string $charset
	 * @return string|string[]|null
	 */
	public static function htmlSpecialChars($valores,$charset=CFG_CHARSET){
		if(!$valores){return;}
		
		if(is_array($valores)){
			$valores=self::htmlSpecialCharsArray($valores,$charset);
		}else{
			$valores=self::htmlSpecialCharsScalar($valores,$charset);
		}
		
		return $valores;
	}
	
	/**
	 * Itera a fun��o nativa htmlspecialchars sobre um vetor
	 * 
	 * @param string[] $valores
	 * @param string $charset
	 * @return string[]
	 */
	private static function htmlSpecialCharsArray(array $valores,$charset){
		foreach($valores as $i=>$valor){
			$valores[$i]=htmlspecialchars($valor,ENT_QUOTES,$charset);
		}
		
		return $valores;
	}
	
	/**
	 * Passa uma string pela fun��o nativa htmlspecialchars
	 * 
	 * @param string $valor
	 * @param string $charset
	 * @return string
	 */
	private static function htmlSpecialCharsScalar($valor,$charset){
		return htmlspecialchars($valor,ENT_QUOTES,$charset);
	}
	
	/**
	 * Encurta a quantidade de caract�res de um valor ou valores
	 * 
	 * @api
	 * @param mixed|mixed[] $valores
	 * @param int $tamanho
	 * @return mixed|mixed[]|null
	 */
	public static function encurtar($valores,$tamanho=5){
		if(!$valores){return;}
	
		if(is_array($valores)){
			$valores=self::encurtarArray($valores,$tamanho);
		}else{
			$valores=self::encurtarScalar($valores,$tamanho);
		}
		
		return $valores;
	}
	
	/**
	 * Itera um vetor encurtando todos os seus valores
	 * 
	 * @param mixed[] $valores
	 * @param int $tamanho
	 * @return mixed[]
	 */
	private static function encurtarArray(array $valores,$tamanho){
		foreach($valores as $i=>$valor){
			if(strlen($valor)>$tamanho+2){
				$valores[$i]=substr($valor,0,$tamanho).'...';
			}
		}
		
		return $valores;
	}
	
	/**
	 * Encurta o tamanho de um valor
	 * 
	 * @param mixed $valor
	 * @param int $tamanho
	 * @return mixed
	 */
	private static function encurtarScalar($valor,$tamanho){
		if(strlen($valor)>$tamanho+2){$valor=substr($valor,0,$tamanho).'...';}
		return $valor;
	}
}
