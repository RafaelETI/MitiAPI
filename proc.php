<?php
//----------sessao----------

if(isset($_POST['login'])==true){
	try{
		$Sessao=new Sessao();
		$Sessao->login();
		header('location:modelo_vis.php'); exit();
	}catch(Exception $e){
		$_SESSION['status']=$e->getMessage();
		header('location:login.php'); exit();
	}
}

if(isset($_POST['logout'])==true){
	try{
		$Sessao=new Sessao();
		$Sessao->logout();
		header('location:login.php'); exit();
	}catch(Exception $e){
		echo $e->getMessage();
	}
}
?>
