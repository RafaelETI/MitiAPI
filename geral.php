<?php
require_once('mod/Config.php');
if($_GET['arquivo']=='login'){$restrito=false;}else{$restrito=true;}
new Config($restrito);

require_once('proc.php');
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="iso-8859-1" />

<title><?php echo SISTEMA; ?></title>

<meta name="author" content="Rafael Barros" />

<link rel="shortcut icon" href="img/fav.png" type="image/png" />

<link rel="stylesheet" href="css/geral.css" />
<link rel="stylesheet" href="css/<?php echo basename($_GET['arquivo']); ?>.css" />
<style></style>

<script src="lib/js/jquery_min.js"></script>
<script src="js/geral.js"></script>
<script src="js/<?php echo basename($_GET['arquivo']); ?>.js"></script>
<script></script>
</head>
<!--==========div==========-->
<body>
<div id="geral">
<?php if($_GET['arquivo']!='login'){ ?>
<div id="nav">
	<div class="header">
		<div class="esquerda">
			<?php echo SISTEMA; ?>
			
			<span id="status">
				<?php
				$MitiStatus=new MitiStatus();
				echo $MitiStatus->obterMensagem();
				?>
			</span>
		</div>
		
		<div class="direita">
			Usuário:
			
			<?php
			$MitiAssinatura=new MitiAssinatura();
			$MitiAssinatura->htmlSpecialChars($_SESSION['login']);
			echo $_SESSION['login'];
			?>
		</div>
	</div>
	
	<div class="section conteudo">
		<div class="esquerda">
			<a id="modelo" class="menu">Modelo</a>
		</div>
		
		<div class="direita">
			<form method="post" action="">
				<input type="submit" name="logout" value="Sair" />
			</form>
		</div>
		
		<div id="modelo_oculto">
			<a href="geral.php?arquivo=modelo_vis">Visualização</a>
			<a>Busca</a>
			<a href="geral.php?arquivo=modelo_ce">Cadastro</a>
		</div>
	</div>
</div>
<?php } ?>

<?php require_once(basename($_GET['arquivo']).'.php'); ?>

<?php
try{
	$MitiCRUD=new MitiCRUD(new ARPessoas());
	//$MitiCRUD->deletar(6);
	//$MitiCRUD->inserir(array('nome'=>'Judith','sexo'=>1));
	//$MitiCRUD->alterar(array('nome'=>'John'),3);
	$MitiCRUD->juntar(new ARSexos(),'sexo','id');
	$MitiCRUD->definirCampos(array('id','nome'),array('nome'));
	$MitiBD=$MitiCRUD->ler();
	
	while(($pessoas=$MitiBD->obterAssoc())==true){
		echo $pessoas['id'].' | '.$pessoas['nome'].' | '.$pessoas['sexos_nome'].'<br />';
	}
}catch(Exception $e){
	echo $e->getMessage();
}
?>
</div>
</body>
</html>
