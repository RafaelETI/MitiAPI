<?php
//----------sessao----------

if(isset($_POST['login'])==true){
	try{
		$Sessao=new Sessao();
		$Sessao->login();
		header('location:geral.php?arquivo=modelo_vis'); exit();
	}catch(Exception $e){
		$_SESSION['status']=$e->getMessage();
		header('location:geral.php?arquivo=login'); exit();
	}
}

if(isset($_POST['logout'])==true){
	try{
		$Sessao=new Sessao();
		$Sessao->logout();
		header('location:geral.php?arquivo=login'); exit();
	}catch(Exception $e){
		echo $e->getMessage();
	}
}
?>
