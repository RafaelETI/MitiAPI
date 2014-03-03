<?php
class Usuario{
	private $MitiCRUD;
	
	public function __construct(){
		$this->MitiCRUD=new MitiCRUD('usuarios');
	}
	
	private function validar(){
		$MitiValidacao=new MitiValidacao();
		$MitiValidacao->vazio($_POST);
	}
	
	private function obterSenha(){
		$this->MitiCRUD->definirCampos(array('senha'));
		return $this->MitiCRUD->ler(array('id'=>array('=',$_POST['usuario'])))->obterAssoc();
	}
	
	public function login(){
		$this->validar();
		$usuarios=$this->obterSenha();
		if($usuarios['senha']!=crypt($_POST['senha'],$usuarios['senha'])){throw new Exception('Autenticação inválida');}
		$_SESSION['login']=$_POST['usuario'];
	}
	
	public function logout(){
		session_destroy();
	}
}
?>
