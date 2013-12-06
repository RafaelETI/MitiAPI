<?php
class MitiParcialidade{
	private static $exclusivos=array();
	private static $excecoes=array();
	private $vetor;
	
	public function setExclusivos(array $exclusivos){
		self::$exclusivos=$exclusivos;
	}
	
	public function setExcecoes(array $excecoes){
		self::$excecoes=$excecoes;
	}
	
	public function preparar(&$string){
		$this->vetor=is_array($string);
		if($this->vetor==false){
			settype($string,'array');
		}
	}
	
	public function parcializar($v){
		foreach(self::$excecoes as $w){
			if($v==$w){return true;}
		}

		foreach(self::$exclusivos as $w){
			foreach(self::$exclusivos as $x){
				if($v==$x){break 2;}
			}
	
			if($v!=$w){return true;}
		}
	}
	
	public function finalizar(&$string){
		if($this->vetor==false){$string=$string[0];}
	}
}
?>
