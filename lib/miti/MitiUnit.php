<?php
class MitiUnit{
	private function imprimir($title,$cor){
		$MitiTratamento=new MitiTratamento();
		$MitiTratamento->htmlSpecialChars($title);
		echo '<div title="'.$title.'" style="height:20px; width:20px; border:solid 1px; float:left; cursor:help; background:'.$cor.';"></div>';
	}
	
	public function aguardar($title){
		$this->imprimir($title,'orange');
	}
	
	public function afirmar($valores,$afirmacao,$title){
		$cor='green';
		
		if(is_array($valores)==false){
			if($valores!==$afirmacao){
				$cor='red';
				$title.=': Valor: '.$valores.'; Afirmação: '.$afirmacao;
			}
		}else{
			foreach($valores as $i=>$v){
				if($v!==$afirmacao[$i]){
					$cor='red';
					$title.=': Valor: '.$v.'; Afirmação: '.$afirmacao[$i];
					break;
				}
			}
		}
		
		$this->imprimir($title,$cor);
	}
}
?>
