<?php
class MitiStatus{
	public function obterAlerta($mensagem,$url){
		$this->verificarSucesso($mensagem);
		
		$js='<script>';
		$js.='alert("'.$mensagem.'");';
		$js.='location.href="'.$url.'";';
		$js.='</script>';
		
		return $js;
	}
	
	private function verificarSucesso(&$mensagem){
		if($mensagem===true){
			$mensagem='Concluído com sucesso';
		}
	}
}