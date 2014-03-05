<?php
class MitiTratamentoUnit extends MitiUnit{
	private $MitiTratamento;
	
	public function __construct(){
		$this->MitiTratamento=new MitiTratamento();
		
		$this->htmlSpecialCharsString();
		$this->htmlSpecialCharsArray();
		$this->encurtarString();
		$this->encurtarArray();
		$this->removerAcentosString();
		$this->removerAcentosArray();
	}
	
	private function htmlSpecialCharsString(){
		$teste='\'"&<>';
		$this->MitiTratamento->htmlSpecialChars($teste);
		$this->afirmar($teste,'&#039;&quot;&amp;&lt;&gt;',__METHOD__);
	}
	
	private function htmlSpecialCharsArray(){
		$teste=array("'",'"','&','<','>');
		$this->MitiTratamento->htmlSpecialChars($teste);
		$this->afirmar($teste,array('&#039;','&quot;','&amp;','&lt;','&gt;'),__METHOD__);
	}
	
	private function encurtarString(){
		$teste='aaaaaaaaaa';
		$this->MitiTratamento->encurtar($teste);
		$this->afirmar($teste,'aaaaa...',__METHOD__);
	}
	
	private function encurtarArray(){
		$teste=array('aaaaaaaaaa','bbbbbbbbbb','cccccccccc');
		$this->MitiTratamento->encurtar($teste);
		$this->afirmar($teste,array('aaaaa...','bbbbb...','ccccc...'),__METHOD__);
	}
	
	private function removerAcentosString(){
		$teste='ç';
		$this->MitiTratamento->removerAcentos($teste);
		$this->afirmar($teste,'c',__METHOD__);
	}
	
	private function removerAcentosArray(){
		$teste=array('á','È','î','Õ','ü','Ç');
		$this->MitiTratamento->removerAcentos($teste);
		$this->afirmar($teste,array('a','E','i','O','u','C'),__METHOD__);
	}
}
?>
