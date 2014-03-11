<?php
class MitiPaginacao{
	private $num_reg;
	private $pg;
	private $inicio;
	private $total;
	private $max_links;
	private $qnt_pg;
	private $link_inicial;
	private $link_final;

	public function __construct($num_reg,$pg,$max_links){
		$this->num_reg=$num_reg;
		$this->pg=$pg;
		$this->max_links=$max_links;
		
		$this->inicio=$this->pg-1;
		$this->inicio*=$num_reg;
	}
	
	public function setTotal($total){
		$this->total=$total;
	}
	
	public function getNumReg(){
		//chama-lo na definicao do limite de registros
		return $this->num_reg;
	}
	
	public function getInicio(){
		//chama-lo na definicao do limite de registros
		return $this->inicio;
	}
	
	public function criar($url,$off='',$on=''){
		$this->calcular();
	
		if($this->pg!==1){$paginacao='<a href="'.$url.'1">Primeira</a>';}
		else{$paginacao='<span class="'.$off.'">Primeira</span>';}

		if($this->pg>1){$paginacao.='<a href="'.$url.($this->pg-1).'">Anterior</a>';}
		else{$paginacao.='<span class="'.$off.'">Anterior</span>';}

		for($x=$this->link_inicial;$x<=$this->link_final;$x++){
			if($this->pg==$x){$paginacao.='<span class="'.$on.'">'.$x.'</span>';}
			else{
				if($x<1||$x>=$this->qnt_pg){continue;}
				$paginacao.='<a href="'.$url.$x.'">'.$x.'</a>';
			}
		}

		if(($this->pg+1)<$this->qnt_pg){$paginacao.='<a href="'.$url.($this->pg+1).'">Próxima</a>';}
		else{$paginacao.='<span class="'.$off.'">Próxima</span>';}

		if($this->pg!==$this->qnt_pg-1){$paginacao.='<a href="'.$url.($this->qnt_pg-1).'">Última</a>';}
		else{$paginacao.='<span class="'.$off.'">Última</span>';}
		
		return $paginacao;
	}
	
	private function calcular(){
		$this->qnt_pg=ceil($this->total/$this->num_reg);
		$this->qnt_pg++;
		
		$metade=ceil($this->max_links/2);
		$metade--;
		$this->link_inicial=$this->pg-$metade;
		$this->link_final=$this->pg+$metade;
	}
}