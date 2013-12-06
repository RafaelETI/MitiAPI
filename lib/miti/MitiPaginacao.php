<?php
class MitiPaginacao{
	private $num_reg;
	private $pg;
	private $inicio;

	public function __construct($num_reg,$pg){
		$this->num_reg=$num_reg;
		$this->pg=$pg;
		
		$inicio=$this->pg-1;
		$inicio*=$num_reg;
		$this->inicio=$inicio;
	}
	
	public function getNumReg(){
		//chama-lo na definicao do limite de registros
		return $this->num_reg;
	}
	
	public function getInicio(){
		//chama-lo na definicao do limite de registros
		return $this->inicio;
	}
	
	public function criar($url,$total,$max_links,$off='',$on=''){
		//calculos
		$qnt_pg=ceil($total/$this->num_reg);
		$qnt_pg++;
		
		$metade=ceil($max_links/2);
		$metade--;
		$link_inicial=$this->pg-$metade;
		$link_final=$this->pg+$metade;
		
		//html
		$paginacao='<a href="'.$url.'1">Primeira</a>';

		if($this->pg>1){$paginacao.='<a href="'.$url.($this->pg-1).'">Anterior</a>';}
		else{$paginacao.='<span class="'.$off.'">Anterior</span>';}

		for($x=$link_inicial;$x<=$link_final;$x++){
			if($this->pg==$x){$paginacao.='<span id="'.$on.'">'.$x.'</span>';}
			else{
				if($x<1||$x>=$qnt_pg){continue;}
				$paginacao.='<a href="'.$url.$x.'">'.$x.'</a>';
			}
		}

		if(($this->pg+1)<$qnt_pg){$paginacao.='<a href="'.$url.($this->pg+1).'">Próxima</a>';}
		else{$paginacao.='<span class="'.$off.'">Próxima</span>';}

		$paginacao.='<a href="'.$url.($qnt_pg-1).'">Última</a>';
		
		//retorno
		return $paginacao;
	}
}
?>
