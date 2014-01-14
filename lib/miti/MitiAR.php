<?php
class MitiAR{
	private $tabela;
	private $pk;
	private $tipos=array();
	private $anulaveis=array();
	private $tamanhos=array();
	
	public function __construct($tabela){
		//tabela
		$this->tabela=$tabela;
		
		//obter campos
		$MitiBD=new MitiBD();
		$MitiBD->requisitar('select * from '.$this->tabela);
		$MitiBD->fechar();
		$campos=$MitiBD->obterCampos();
		
		//pk
		foreach($campos as $o){
			if(($o->flags&2)==true){
				$this->pk=$o->orgname;
				break;
			}
		}
		
		//tipos
		foreach($campos as $o){
			if(($o->flags&32768)==true){
				$this->tipos[$o->orgname]='float';
			}else{
				$this->tipos[$o->orgname]='string';
			}
		}
		
		//anulaveis
		foreach($campos as $o){
			if($o->flags&1){
				$this->anulaveis[$o->orgname]=false;
			}else{
				$this->anulaveis[$o->orgname]=true;
			}
		}
		
		//tamanhos
		foreach($campos as $o){
			$this->tamanhos[$o->orgname]=$o->length;
		}
	}
	
	public function getTabela(){
		return $this->tabela;
	}

	public function getTipos(){
		return $this->tipos;
	}
	
	public function getAnulaveis(){
		return $this->anulaveis;
	}
	
	public function getTamanhos(){
		return $this->tamanhos;
	}
	
	public function getPkCampo(){
		return $this->pk;
	}
	
	public function getPkTipo(){
		return $this->tipos[$this->pk];
	}
}
?>
