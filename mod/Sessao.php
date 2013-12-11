<?php
class Sessao{
	public function login(){
		//validacoes
		$MitiValidacao=new MitiValidacao();
		$MitiValidacao->vazio($_POST);
		
		//banco
		$MitiBD=new MitiBD();
		$MitiBD->escapar($_POST);
		$sql='select senha from sessao where usuario="'.$_POST['usuario'].'"';
		$MitiBD->requisitar($sql);
		$MitiBD->fechar();
		
		//autenticacao
		$sessao=$MitiBD->obterAssoc();
		if($sessao['senha']!=crypt($_POST['senha'],$sessao['senha'])){throw new Exception('Autenticação inválida');}
		
		//sessao
		$_SESSION['login']=$_POST['usuario'];
	}
	
	public function logout(){
		session_destroy();
	}
}
?>
