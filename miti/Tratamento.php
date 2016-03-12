<?php
/**
 * Miti API, 2014 - 2015
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
		$hash = md5(file_get_contents($caminho));
		
		$partes = explode('.', $caminho);
		$extensao = end($partes);
		
		return $extensao === 'js'?
			"<script src='$caminho?hash=$hash'></script>\n":
			"<link rel='stylesheet' href='$caminho?hash=$hash' />\n"
		;
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
	public static function indexar($vetor, array $indices, $valor = ''){
		foreach($indices as $indice){
			if(!isset($vetor[$indice])){$vetor[$indice] = $valor;}
		}
		
		return $vetor;
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
	public static function escapar($valores, $charset = 'UTF-8'){
		if(!$valores){return;}
		return is_array($valores)? self::escaparArray($valores,$charset): self::escaparScalar($valores,$charset);
	}
	
	private static function escaparArray(array $valores, $charset){
		foreach($valores as &$valor){$valor = self::escaparScalar($valor, $charset);}
		return $valores;
	}
	
	private static function escaparScalar($valor, $charset){
		return htmlspecialchars($valor, ENT_QUOTES, $charset);
	}
	
	/**
	 * Encurta a quantidade de caractéres de um valor
	 * 
	 * @api
	 * @param mixed|mixed[] $valores
	 * @param int $tamanho
	 * @return mixed|mixed[]|null
	 */
	public static function encurtar($valores, $tamanho){
		if(!$valores){return;}
		return is_array($valores)? self::encurtarArray($valores,$tamanho): self::encurtarScalar($valores,$tamanho);
	}
	
	private static function encurtarArray(array $valores, $tamanho){
		foreach($valores as &$valor){$valor = self::encurtarScalar($valor, $tamanho);}
		return $valores;
	}
	
	private static function encurtarScalar($valor, $tamanho){
		if(strlen($valor) > $tamanho + 2){$valor = mb_substr($valor, 0, $tamanho).'...';}
		return $valor;
	}
}
