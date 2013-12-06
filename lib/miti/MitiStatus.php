<?php
class MitiStatus{
	private function sucesso(&$mensagem){
		if($mensagem===true){$mensagem='O procedimento foi realizado com sucesso';}
	}

	public function obterMensagem(){
		if(isset($_SESSION['status'])==false){return null;}
		
		$mensagem=$_SESSION['status'];
		unset($_SESSION['status']);
		
		$this->sucesso($mensagem);
		
		return $mensagem;
	}
	
	public function obterAlerta($mensagem,$url){
		$this->sucesso($mensagem);
	
		return
			'<script>'.
				'alert("'.$mensagem.'");'.
				'location.href="'.$url.'";'.
			'</script>'
		;
	}
}
?>
