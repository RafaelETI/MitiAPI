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
	 * Cria o html para inclusão de arquivos CSS e JS
	 * 
	 * A diferença de chamar diretamente é que esse método parametriza o link
	 * com o hash do arquivo, fazendo com que sempre que haja uma alteração
	 * no conteúdo do arquivo, o navegador veja como um novo arquivo e não use
	 * o que já está em cache.
	 * 
	 * Não é interessante de se usar com arquivos de terceiros, visto que esses,
	 * à princípio, não são alterados permanecendo com o mesmo nome.
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
	 * Garante a existência de índices de um vetor
	 * 
	 * O valor com o qual os índices são inicializados é passado por parâmetro.
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
	 * Garante o conteúdo do código fonte de um arquivo
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
	 * Extende a capacidade da função nativa htmlspecialchars()
	 * 
	 * Faz com que ela aceite também vetores, que considere aspas, e que
	 * considere outra codificação por padrão.
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
	 * Itera a função nativa htmlspecialchars sobre um vetor
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
	 * Passa uma string pela função nativa htmlspecialchars
	 * 
	 * @param string $valor
	 * @param string $charset
	 * @return string
	 */
	private static function htmlSpecialCharsScalar($valor,$charset){
		return htmlspecialchars($valor,ENT_QUOTES,$charset);
	}
	
	/**
	 * Encurta a quantidade de caractéres de um valor ou valores
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
