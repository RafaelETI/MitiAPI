<?php
class Usuario{
	private $MitiORM;
	
	public function __construct(){
		$this->MitiORM=new MitiORM('usuarios');
	}
	
	public function login(){
		$this->validar();
		$usuarios=$this->obterSenha();
		if($usuarios['senha']!=crypt($_POST['senha'],$usuarios['senha'])){throw new Exception('Autenticação inválida');}
		$_SESSION['login']=$_POST['usuario'];
	}
	
	private function validar(){
		$MitiValidacao=new MitiValidacao();
		$MitiValidacao->vazio($_POST);
	}
	
	private function obterSenha(){
		$this->MitiORM->definirCampos(array('senha'));
		return $this->MitiORM->ler(array('id'=>array('=',$_POST['usuario'])))->obterAssoc();
	}
	
	public function logout(){
		session_destroy();
	}
}
?>
