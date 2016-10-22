<?php
namespace Miti;

class Paginacao{
	private $total;
	private $quantidade;
	private $pagina;
	private $quantidadeDeBotoes;
	private $inicio;
	private $quantidadeDePaginas;
	private $botaoInicial;
	private $botaoFinal;
	
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
	
	public function getInicio(){return $this->inicio;}
	
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
