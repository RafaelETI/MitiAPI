<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Pagina��o HTML
 * 
 * Dica: considerando os poss�veis filtros na defini��o do total de registros e
 * passando esses filtros na query string dos bot�es html, consegue-se realizar
 * uma pagina��o que leva em conta a busca do usu�rio.
 */
class Paginacao{
	/**
	 * @var int Total de registros selecionados na requisi��o.
	 */
	private $total;
	
	/**
	 * @var int Quantidade de registros por p�gina.
	 */
	private $quantidade;
	
	/**
	 * @var string P�gina atual. O tipo � string porque esse valor geralmente
	 * vem pela super global $_GET.
	 */
	private $pagina;
	
	/**
	 * @var int Quantidade total de bot�es simult�neos para troca de p�gina.
	 */
	private $quantidadeDeBotoes;
	
	/**
	 * @var int Posi��o do registro inicial da p�gina.
	 */
	private $inicio;
	
	/**
	 * @var int Quantidade total de p�ginas.
	 */
	private $quantidadeDePaginas;
	
	/**
	 * @var int N�mero da p�gina do bot�o inicial.
	 */
	private $botaoInicial;
	
	/**
	 * @var int N�mero da p�gina do bot�o final.
	 */
	private $botaoFinal;
	
	/**
	 * Define as propriedades
	 * 
	 * Os par�metros passados de forma arbitr�ria pelo usu�rio s�o: $quantidade,
	 * $pagina, $quantidadeDeBotoes, e $filtros. O total deve ser conseguido atrav�s
	 * de uma requisi��o ao banco, e o resto, atr�ves de c�lculos.
	 * 
	 * Recomenda-se que o valor do $quantidadeDeBotoes seja �mpar para que o bot�o
	 * da p�gina atual fique no centro e tenha a mesma quantidade de bot�es de
	 * cada lado.
	 * 
	 * @api
	 * @param int $total
	 * @param int $quantidade
	 * @param string $pagina
	 * @param int $quantidadeDeBotoes
	 */
	public function __construct($total, $quantidade, $pagina, $quantidadeDeBotoes){
		$this->total = $total;
		$this->quantidade = $quantidade;
		$this->pagina = $pagina;
		$this->quantidadeDeBotoes = $quantidadeDeBotoes;
		
		$this->inicio = ($this->pagina - 1) * $this->quantidade;
		
		$this->quantidadeDePaginas = ceil($this->total / $this->quantidade) + 1;
		
		$metade = ceil($this->quantidadeDeBotoes / 2) - 1;
		$this->botaoInicial = $this->pagina - $metade;
		$this->botaoFinal = $this->pagina + $metade;
	}
	
	/**
	 * Retorna o registro inicial da busca no banco
	 * 
	 * Deve ser usado como o segundo par�metro da instru��o SQL de limite.
	 * 
	 * N�o h� um getLimite() porque esse valor � definido arbitrariamente pelo
	 * usu�rio que j� o usa do lado de fora.
	 * 
	 * @api
	 * @return int
	 */
	public function getInicio(){
		return $this->inicio;
	}
	
	/**
	 * Cria a pagina��o em HTML
	 * 
	 * S�o gerados os bot�es: Primeira, Anterior, Pr�xima, e �ltima, al�m dos
	 * n�mericos incrementados de um em um, com o limite definido pelo usu�rio.
	 * 
	 * @api
	 * @param string $pagina Nome do par�metro do valor da p�gina.
	 * @param string $on Nome da classe css que estiliza o bot�o ativo.
	 * @param string $off Nome da classe css que estiliza os bot�es desativados.
	 * @param string[] $filtros No formato "campo => valor".
	 * @return string
	 */
	public function criar($pagina, $on = '', $off = '', array $filtros = array()){
		$queryString = $this->formatarQueryString($filtros, $pagina);
		
		$paginacao = $this->pagina != 1?
			"<a href='?$queryString=1'>Primeira</a>":
			"<span class='$off'>Primeira</span>";
		
		$paginacao .= $this->pagina > 1?
			"<a href='?$queryString=".($this->pagina - 1)."'>Anterior</a>":
			"<span class='$off'>Anterior</span>";
		
		for($botao = $this->botaoInicial; $botao <= $this->botaoFinal; $botao++){
			if($this->pagina == $botao){
				$paginacao .= "<span class='$on'>$botao</span>";
			}else{
				if($botao < 1 || $botao >= $this->quantidadeDePaginas){continue;}
				$paginacao .= "<a href='?$queryString=$botao'>$botao</a>";
			}
		}
		
		$paginacao .= ($this->pagina + 1) < $this->quantidadeDePaginas?
			"<a href='?$queryString=".($this->pagina + 1)."'>Pr�xima</a>":
			"<span class='$off'>Pr�xima</span>";
		
		$paginacao .= $this->pagina != ($this->quantidadeDePaginas - 1)?
			"<a href='?$queryString=".($this->quantidadeDePaginas - 1)."'>�ltima</a>":
			"<span class='$off'>�ltima</span>";
		
		return $paginacao;
	}
	
	/**
	 * Formata o vetor de filtros em texto, no padr�o de uma query string
	 * 
	 * @param string[] $filtros
	 * @param string $pagina
	 * @return string
	 */
	private function formatarQueryString(array $filtros, $pagina){
		$queryString = array();
		
		foreach($filtros as $campo => $valor){
			if($campo === $pagina){continue;}
			$queryString[] = "$campo=$valor";
		}
		
		$queryString = implode('&amp;', $queryString);
		$queryString .= $queryString? '&amp;'.$pagina: $pagina;
		return $queryString;
	}
}
