<?php
//----------sessao----------

//login
if(isset($_POST['login'])==true){
	try{
		$Sessao=new Sessao();
		$Sessao->login();
		header('location:geral.php?arquivo=teste_vis'); exit();
	}catch(Exception $e){
		$_SESSION['status']=$e->getMessage();
		header('location:geral.php?arquivo=login'); exit();
	}
}

//logout
if(isset($_POST['logout'])==true){
	$Sessao=new Sessao();
	$Sessao->logout();
	header('location:geral.php?arquivo=login'); exit();
}
?>
