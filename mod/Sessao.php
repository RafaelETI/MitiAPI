<?php
class Sessao{
	public function login(){
		//validacoes
		$MitiValidacao=new MitiValidacao();
		$MitiValidacao->vazio($_POST);
		
		//banco
		$MitiCRUD=new MitiCRUD(new ARSessao());
		$MitiCRUD->definirCampos(array('senha'));
		$MitiBD=$MitiCRUD->ler(array('usuario'=>array('=',$_POST['usuario'])));
		
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
