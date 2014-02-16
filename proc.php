<?php
//----------usuario----------

if(isset($_POST['login'])){
	try{
		$Usuario=new Usuario();
		$Usuario->login();
		header('location:modelo_vis.php'); exit();
	}catch(Exception $e){
		$_SESSION['status']=$e->getMessage();
		header('location:login.php'); exit();
	}
}

if(isset($_POST['logout'])){
	try{
		$Usuario=new Usuario();
		$Usuario->logout();
		header('location:login.php'); exit();
	}catch(Exception $e){
		echo $e->getMessage();
	}
}
?>
