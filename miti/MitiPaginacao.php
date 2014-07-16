<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Paginação HTML
 * 
 * Dica: considerando os possíveis filtros na definição do total de registros e
 * passando esses filtros na query string dos botões html, consegue-se realizar
 * uma paginação que leva em conta a busca do usuário.
 */
class MitiPaginacao{
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
	private $quantidadeBotoes;
	
	/**
	 * @var int Posição do registro inicial da página.
	 */
	private $inicio;
	
	/**
	 * @var int Quantidade total de páginas.
	 */
	private $quantidadePaginas;
	
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
	 * $pagina, e $quantidadeBotoes. O total deve ser conseguido através de uma
	 * requisição ao banco, e o resto, atráves de cálculos.
	 * 
	 * Recomenda-se que o valor do $quantidadeBotoes seja ímpar para que o botão
	 * da página atual fique no centro e tenha a mesma quantidade de botões de
	 * cada lado.
	 * 
	 * @api
	 * @param int $total
	 * @param int $quantidade
	 * @param string $pagina
	 * @param int $quantidadeBotoes
	 */
	public function __construct($total,$quantidade,$pagina,$quantidadeBotoes){
		$this->total=$total;
		$this->quantidade=$quantidade;
		$this->pagina=$pagina;
		$this->quantidadeBotoes=$quantidadeBotoes;
		
		$this->inicio=($this->pagina-1)*$this->quantidade;
		
		$this->quantidadePaginas=ceil($this->total/$this->quantidade)+1;
		
		$metade=ceil($this->quantidadeBotoes/2)-1;
		$this->botaoInicial=$this->pagina-$metade;
		$this->botaoFinal=$this->pagina+$metade;
	}
	
	/**
	 * Retorna o registro inicial da busca no banco
	 * 
	 * Deve ser usado como o segundo parâmetro da instrução SQL de limite.
	 * 
	 * Não há um getLimite() porque esse valor é definido arbitrariamente pelo
	 * usuário que já o usa do lado de fora.
	 * 
	 * @api
	 * @return int
	 */
	public function getInicio(){
		return $this->inicio;
	}
	
	/**
	 * Cria a paginação em HTML
	 * 
	 * Caso não haja um total de registros maior que zero, é retornada uma
	 * mensagem de status.
	 * 
	 * São gerados os botões: Primeira, Anterior, Próxima, e Última, além dos
	 * númericos incrementados de um em um, com o limite definido pelo usuário.
	 * 
	 * @api
	 * @param string $url Pedaço da URL que antecede o número da página.
	 * @param string $on Nome da classe css que estiliza o botão para ativado.
	 * @param string $off Nome da classe css que estiliza o botão para desativado.
	 * @return string
	 */
	public function criar($url,$on='',$off=''){
		if(!$this->total){
			return 'Não há registros para esta busca';
		}
		
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
}
