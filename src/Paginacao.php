<?php
/**
 * Miti API, 2014 - 2015
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Paginação HTML
 * 
 * Dica: considerando os possíveis filtros na definição do total de registros e
 * passando esses filtros na query string dos botões html, consegue-se realizar
 * uma paginação que leva em conta a busca do usuário.
 */
class Paginacao{
	/**
	 * @var int Total de registros selecionados na requisição.
	 */
	private $total;
	
	/**
	 * @var int Quantidade de registros por página.
	 */
	private $quantidade;
	
	/**
	 * @var string Página atual. O tipo é string porque esse valor geralmente
	 * vem pela super global $_GET.
	 */
	private $pagina;
	
	/**
	 * @var int Quantidade total de botões simultâneos para troca de página.
	 */
	private $quantidadeDeBotoes;
	
	/**
	 * @var int Posição do registro inicial da página.
	 */
	private $inicio;
	
	/**
	 * @var int Quantidade total de páginas.
	 */
	private $quantidadeDePaginas;
	
	/**
	 * @var int Número da página do botão inicial.
	 */
	private $botaoInicial;
	
	/**
	 * @var int Número da página do botão final.
	 */
	private $botaoFinal;
	
	/**
	 * Define as propriedades
	 * 
	 * Os parâmetros passados de forma arbitrária pelo usuário são: $quantidade,
	 * $pagina, $quantidadeDeBotoes, e $filtros. O total deve ser conseguido através
	 * de uma requisição ao banco, e o resto, atráves de cálculos.
	 * 
	 * Recomenda-se que o valor do $quantidadeDeBotoes seja ímpar para que o botão
	 * da página atual fique no centro e tenha a mesma quantidade de botões de
	 * cada lado.
	 * 
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
	 * Deve ser usado como o segundo parâmetro da instrução SQL de limite.
	 * 
	 * Não há um getLimite() porque esse valor é definido arbitrariamente pelo
	 * usuário que já o usa do lado de fora.
	 * 
	 * @return int
	 */
	public function getInicio(){return $this->inicio;}
	
	/**
	 * Cria a paginação em HTML
	 * 
	 * São gerados os botões: &#8634; (primeira), &#8592; (anterior), &#8594;
	 * (próxima), e &#8635; (última), além dos númericos incrementados de um em
	 * um, com o limite definido pelo usuário.
	 * 
	 * @param string $pagina Nome do parâmetro do valor da página.
	 * @param string $on Nome da classe css que estiliza o botão ativo.
	 * @param string $off Nome da classe css que estiliza os botões desativados.
	 * @param string[] $filtros No formato "campo => valor".
	 * 
	 * @return string
	 */
	public function criar($pagina, $on = '', $off = '', array $filtros = array()){
		$queryString = $this->formatarQueryString($filtros, $pagina);
		
		$paginacao = $this->pagina != 1?
			"<a href='?$queryString=1'>&#8634;</a>":
			"<span class='$off'>&#8634;</span>"
		;
		
		$paginacao .= $this->pagina > 1?
			"<a href='?$queryString=".($this->pagina - 1)."'>&#8592;</a>":
			"<span class='$off'>&#8592;</span>"
		;
		
		for($botao = $this->botaoInicial; $botao <= $this->botaoFinal; $botao++){
			if($this->pagina == $botao){
				$paginacao .= "<span class='$on'>$botao</span>";
			}else{
				if($botao < 1 || $botao >= $this->quantidadeDePaginas){continue;}
				$paginacao .= "<a href='?$queryString=$botao'>$botao</a>";
			}
		}
		
		$paginacao .= ($this->pagina + 1) < $this->quantidadeDePaginas?
			"<a href='?$queryString=".($this->pagina + 1)."'>&#8594;</a>":
			"<span class='$off'>&#8594;</span>"
		;
		
		$paginacao .= $this->pagina != ($this->quantidadeDePaginas - 1)?
			"<a href='?$queryString=".($this->quantidadeDePaginas - 1)."'>&#8635;</a>":
			"<span class='$off'>&#8635;</span>"
		;
		
		return $paginacao;
	}
	
	/**
	 * Formata o vetor de filtros em texto, no padrão de uma query string
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
