<?php
/**
 * MitiAPI, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Pacote de opera��es de tratamento de valor
 */
class MitiTratamento{
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
	public function requerer($caminho){
		$hash=md5(file_get_contents($caminho));
		
		$partes=explode('.',$caminho);
		$extensao=array_pop($partes);
		
		if($extensao==='js'){
			$html="<script src='$caminho?hash=$hash'></script>\n";
		}else if($extensao==='css'){
			$html="<link rel='stylesheet' type='text/css' href='$caminho?hash=$hash' />\n";
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
	public function substituirValor($valor,$condicao,$novo){
		if($valor===$condicao){
			$valor=$novo;
		}
		
		return $valor;
	}
	
	/**
	 * Garante a exist�ncia de �ndices de um vetor
	 * 
	 * Se o �ndice da itera��o n�o existir, recebe uma string vazia.
	 * 
	 * @api
	 * @param mixed[] $vetor
	 * @param string[] $indices
	 * @return mixed[]
	 */
	public function garantirIndices($vetor,array $indices){
		foreach($indices as $i){
			if(!isset($vetor[$i])){
				$vetor[$i]='';
			}
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
	public function garantirArquivo($arquivo,$caminho){
		if(!$arquivo){
			$arquivo=file_get_contents($caminho);
		}
		
		return $arquivo;
	}
	
	/**
	 * Extende a capacidade da fun��o nativa htmlspecialchars()
	 * 
	 * Faz com que ela aceite tamb�m vetores, que considere aspas, e que
	 * considere a codifica��o iso-8859-1 por padr�o.
	 * 
	 * @api
	 * @param string|string[] $valores
	 * @param string $charset
	 * @return string|string[]|null
	 */
	public function htmlSpecialChars($valores,$charset='iso-8859-1'){
		if(!$valores){
			return;
		}
		
		if(is_array($valores)){
			$valores=$this->htmlSpecialCharsArray($valores,$charset);
		}else{
			$valores=$this->htmlSpecialCharsScalar($valores,$charset);
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
	private function htmlSpecialCharsArray(array $valores,$charset){
		foreach($valores as $i=>$v){
			$valores[$i]=htmlspecialchars($v,ENT_QUOTES,$charset);
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
	private function htmlSpecialCharsScalar($valor,$charset){
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
	public function encurtar($valores,$tamanho=5){
		if(!$valores){
			return;
		}
	
		if(is_array($valores)){
			$valores=$this->encurtarArray($valores,$tamanho);
		}else{
			$valores=$this->encurtarScalar($valores,$tamanho);
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
	private function encurtarArray(array $valores,$tamanho){
		foreach($valores as $i=>$v){
			if(strlen($v)>$tamanho+2){
				$valores[$i]=substr($v,0,$tamanho).'...';
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
	private function encurtarScalar($valor,$tamanho){
		if(strlen($valor)>$tamanho+2){
			$valor=substr($valor,0,$tamanho).'...';
		}
		
		return $valor;
	}
	
	/**
	 * Remove os acentos de um valor ou valores
	 * 
	 * @api
	 * @param string|string[] $valores
	 * @return string|string[]|null
	 */
	public function removerAcentos($valores){
		if(!$valores){
			return;
		}
	
		$acentos=array(
			'�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�',
			'�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�',
			'�','�','�','�','�','�','�','�','�','�','�','�'
		);
		
		$normais=array(
			'a','a','a','a','a','e','e','e','e','i','i','i','i','o','o','o','o',
			'o','u','u','u','u','c','A','A','A','A','A','E','E','E','E','I','I',
			'I','I','O','O','O','O','O','U','U','U','U','C'
		);
		
		if(is_array($valores)){
			$valores=$this->removerAcentosArray($valores,$acentos,$normais);
		}else{
			$valores=$this->removerAcentosScalar($valores,$acentos,$normais);
		}
		
		return $valores;
	}
	
	/**
	 * Itera sobre um vetor removendo os acentos de seus valores
	 * 
	 * @param string[] $valores
	 * @param string[] $acentos
	 * @param string[] $normais
	 * @return string[]
	 */
	private function removerAcentosArray(array $valores,$acentos,$normais){
		foreach($valores as $i=>$v){
			$valores[$i]=str_replace($acentos,$normais,$v);
		}
		
		return $valores;
	}
	
	/**
	 * Remove os acentos de um valor
	 * 
	 * @param string $valor
	 * @param string[] $acentos
	 * @param string[] $normais
	 * @return string
	 */
	private function removerAcentosScalar($valor,$acentos,$normais){
		return str_replace($acentos,$normais,$valor);
	}
}
