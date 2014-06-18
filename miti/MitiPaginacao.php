<?php
/**
 * MitiAPI, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
class MitiPaginacao{
	private $total;
	private $quantidade;
	private $pagina;
	private $quantidadeBotoes;
	private $inicio;
	private $quantidadePaginas;
	private $botaoInicial;
	private $botaoFinal;
	
	public function __construct($total,$quantidade,$pagina,$quantidadeBotoes){
		$this->total=$total;
		$this->quantidade=$quantidade;
		$this->pagina=$pagina;
		$this->quantidadeBotoes=$quantidadeBotoes;
		
		$this->inicio=($this->pagina-1)*$quantidade;
	}
	
	public function getInicio(){
		return $this->inicio;
	}
	
	public function criar($url,$off='',$on=''){
		if(!$this->total){
			return 'Não há registros para esta busca';
		}
		
		$this->calcular();
		
		if($this->pagina!=1){
			$paginacao='<a href="'.$url.'1">Primeira</a>';
		}else{
			$paginacao='<span class="'.$off.'">Primeira</span>';
		}
		
		if($this->pagina>1){
			$paginacao.='<a href="'.$url.($this->pagina-1).'">Anterior</a>';
		}else{
			$paginacao.='<span class="'.$off.'">Anterior</span>';
		}
		
		for($x=$this->botaoInicial;$x<=$this->botaoFinal;$x++){
			if($this->pagina==$x){
				$paginacao.='<span class="'.$on.'">'.$x.'</span>';
			}else{
				if($x<1||$x>=$this->quantidadePaginas){
					continue;
				}
				
				$paginacao.='<a href="'.$url.$x.'">'.$x.'</a>';
			}
		}
		
		if(($this->pagina+1)<$this->quantidadePaginas){
			$paginacao.='<a href="'.$url.($this->pagina+1).'">Próxima</a>';
		}else{
			$paginacao.='<span class="'.$off.'">Próxima</span>';
		}
		
		if($this->pagina!=($this->quantidadePaginas-1)){
			$paginacao.='<a href="'.$url.($this->quantidadePaginas-1).'">Última</a>';
		}else{
			$paginacao.='<span class="'.$off.'">Última</span>';
		}
		
		return $paginacao;
	}
	
	private function calcular(){
		$this->quantidadePaginas=ceil($this->total/$this->quantidade)+1;
		
		$metade=ceil($this->quantidadeBotoes/2)-1;
		$this->botaoInicial=$this->pagina-$metade;
		$this->botaoFinal=$this->pagina+$metade;
	}
}
