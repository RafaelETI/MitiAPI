<div id="nav">
	<div class="header">
		<div><?php echo SISTEMA; ?> <span id="status"><?php echo $MitiStatus->obterMensagem(); ?></span></div>
		<div>Usuário: admin</div>
	</div>
	
	<div class="section conteudo">
		<div>
			<a id="modelo" class="menu">Modelo</a>
		</div>
		
		<div>
			<form method="post" action="">
				<input type="submit" name="logout" value="Sair" />
			</form>
		</div>
		
		<div id="modelo_oculto">
			<a href="modelo_vis.php">Visualização</a>
			<a href="modelo_bus.php">Busca</a>
			<a href="modelo_cad.php">Cadastro</a>
		</div>
	</div>
</div>