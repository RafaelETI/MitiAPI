<?php
class Sessao{
	private $MitiCRUD;
	
	public function __construct(){
		$this->MitiCRUD=new MitiCRUD(new ARSessao());
	}

	public function login(){
		//validacoes
		$MitiValidacao=new MitiValidacao();
		$MitiValidacao->vazio($_POST);
		
		//banco
		$this->MitiCRUD->definirCampos(array('senha'));
		$sessao=$this->MitiCRUD->ler(array('usuario'=>array('=',$_POST['usuario'])))->obterAssoc();
		
		//autenticacao
		if($sessao['senha']!=crypt($_POST['senha'],$sessao['senha'])){throw new Exception('Autenticação inválida');}
		
		//sessao
		$_SESSION['login']=$_POST['usuario'];
	}
	
	public function logout(){
		session_destroy();
	}
}
?>
