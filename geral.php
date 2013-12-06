<?php
require_once('mod/Config.php');

if($_GET['arquivo']=='login'){$restrito='';}else{$restrito=true;}

$Config=new Config($restrito);

require_once('proc.php');
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="iso-8859-1" />

<title>Miti Modelo</title>

<meta name="author" content="Rafael Barros" />

<link rel="shortcut icon" href="img/favicon.png" type="image/png" />

<link rel="stylesheet" href="lib/css/estilo.css" />
<style></style>

<script src="lib/js/jquery_min.js"></script>
<script src="lib/js/funcoes.js"></script>
<script src="js/geral.js"></script>
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
			$MitiAssinatura->htmlSpecialChars($_SESSION[SESSAO]);
			echo $_SESSION[SESSAO];
			?>
		</div>
	</div>
	
	<div class="section conteudo">
		<div class="esquerda">
			<a id="teste" class="menu">Teste</a>
		</div>
		
		<div class="direita">
			<form method="post" action="">
				<input type="submit" name="logout" value="Sair" />
			</form>
		</div>
		
		<div id="teste_oculto">
			<a href="geral.php?arquivo=teste">Visualizar</a>
			<a href="geral.php?arquivo=teste">Buscar</a>
			<a href="geral.php?arquivo=teste">Cadastrar</a>
		</div>
	</div>
</div>
<?php } ?>

<?php require_once(basename($_GET['arquivo']).'.php'); ?>
</div>
</body>
</html>
