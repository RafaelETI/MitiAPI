<?php
class MitiTratamento{
	public function htmlSpecialChars(&$string,$charset='iso-8859-1'){
		$MitiParcialidade=new MitiParcialidade();
		$MitiParcialidade->preparar($string);
		
		foreach($string as $i=>$v){
			if($MitiParcialidade->parcializar($v)==true){continue;}
		
			$string[$i]=htmlspecialchars($v,ENT_QUOTES,$charset);
		}
		
		$MitiParcialidade->finalizar($string);
	}

	public function encurtar(&$string,$tamanho=5){
		$MitiParcialidade=new MitiParcialidade();
		$MitiParcialidade->preparar($string);
		
		foreach($string as $i=>$v){
			if($MitiParcialidade->parcializar($v)==true){continue;}
		
			if(strlen($v)>$tamanho+2){
				$string[$i]=substr($v,0,$tamanho).'...';
			}
		}
		
		$MitiParcialidade->finalizar($string);
	}
	
	public function removerAcentos(&$string){
		$MitiParcialidade=new MitiParcialidade();
		$MitiParcialidade->preparar($string);
		
		$acentos=array('á','à','â','ã','ä','é','è','ê','ë','í','ì','î','ï','ó','ò','ô','õ','ö','ú','ù','û','ü','ç','Á','À','Â','Ã','Ä','É','È','Ê','Ë','Í','Ì','Î','Ï','Ó','Ò','Ô','Õ','Ö','Ú','Ù','Û','Ü','Ç');
		$normais=array('a','a','a','a','a','e','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','c','A','A','A','A','A','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','C');
		
		foreach($string as $i=>$v){
			if($MitiParcialidade->parcializar($v)==true){continue;}
		
			$string[$i]=str_replace($acentos,$normais,$v);
		}
		
		$MitiParcialidade->finalizar($string);
	}
}
?>
