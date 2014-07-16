<?php
/**
 * Miti API, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Pagina��o HTML
 * 
 * Dica: considerando os poss�veis filtros na defini��o do total de registros e
 * passando esses filtros na query string dos bot�es html, consegue-se realizar
 * uma pagina��o que leva em conta a busca do usu�rio.
 */
class MitiPaginacao{
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
	private $quantidadeBotoes;
	
	/**
	 * @var int Posi��o do registro inicial da p�gina.
	 */
	private $inicio;
	
	/**
	 * @var int Quantidade total de p�ginas.
	 */
	private $quantidadePaginas;
	
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
	 * $pagina, e $quantidadeBotoes. O total deve ser conseguido atrav�s de uma
	 * requisi��o ao banco, e o resto, atr�ves de c�lculos.
	 * 
	 * Recomenda-se que o valor do $quantidadeBotoes seja �mpar para que o bot�o
	 * da p�gina atual fique no centro e tenha a mesma quantidade de bot�es de
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
	 * Caso n�o haja um total de registros maior que zero, � retornada uma
	 * mensagem de status.
	 * 
	 * S�o gerados os bot�es: Primeira, Anterior, Pr�xima, e �ltima, al�m dos
	 * n�mericos incrementados de um em um, com o limite definido pelo usu�rio.
	 * 
	 * @api
	 * @param string $url Peda�o da URL que antecede o n�mero da p�gina.
	 * @param string $on Nome da classe css que estiliza o bot�o para ativado.
	 * @param string $off Nome da classe css que estiliza o bot�o para desativado.
	 * @return string
	 */
	public function criar($url,$on='',$off=''){
		if(!$this->total){
			return 'N�o h� registros para esta busca';
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
			$paginacao.='<a href="'.$url.($this->pagina+1).'">Pr�xima</a>';
		}else{
			$paginacao.='<span class="'.$off.'">Pr�xima</span>';
		}
		
		if($this->pagina!=($this->quantidadePaginas-1)){
			$paginacao.='<a href="'.$url.($this->quantidadePaginas-1).'">�ltima</a>';
		}else{
			$paginacao.='<span class="'.$off.'">�ltima</span>';
		}
		
		return $paginacao;
	}
}
