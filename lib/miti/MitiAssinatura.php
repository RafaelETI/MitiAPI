<?php
class MitiAssinatura{
	public function htmlSpecialChars(&$string,$charset='iso-8859-1'){
		$MitiParcialidade=new MitiParcialidade();
		$MitiParcialidade->preparar($string);
		
		foreach($string as $i=>$v){
			if($MitiParcialidade->parcializar($v)==true){continue;}
		
			$string[$i]=htmlspecialchars($v,ENT_QUOTES,$charset);
		}
		
		$MitiParcialidade->finalizar($string);
	}
}
?>
