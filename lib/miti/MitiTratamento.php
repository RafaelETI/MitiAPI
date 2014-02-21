<?php
class MitiTratamento{
	public function htmlSpecialChars(&$valores,$charset='iso-8859-1'){
		if(!is_array($valores)){
			$valores=htmlspecialchars($valores,ENT_QUOTES,$charset);
		}else{
			foreach($valores as $i=>$v){$valores[$i]=htmlspecialchars($v,ENT_QUOTES,$charset);}
		}
	}
	
	public function encurtar(&$valores,$tamanho=5){
		if(!is_array($valores)){
			if(strlen($valores)>$tamanho+2){
				$valores=substr($valores,0,$tamanho).'...';
			}
		}else{
			foreach($valores as $i=>$v){
				if(strlen($v)>$tamanho+2){
					$valores[$i]=substr($v,0,$tamanho).'...';
				}
			}
		}
	}
	
	public function removerAcentos(&$valores){
		$acentos=array('á','à','â','ã','ä','é','è','ê','ë','í','ì','î','ï','ó','ò','ô','õ','ö','ú','ù','û','ü','ç','Á','À','Â','Ã','Ä','É','È','Ê','Ë','Í','Ì','Î','Ï','Ó','Ò','Ô','Õ','Ö','Ú','Ù','Û','Ü','Ç');
		$normais=array('a','a','a','a','a','e','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','c','A','A','A','A','A','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','C');
		
		if(!is_array($valores)){
			$valores=str_replace($acentos,$normais,$valores);
		}else{
			foreach($valores as $i=>$v){$valores[$i]=str_replace($acentos,$normais,$v);}
		}
	}
}
?>