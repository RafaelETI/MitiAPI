<div id="nav">
	<div class="header">
		<div><?php echo SISTEMA ?></div>
		<div>Usuário: admin</div>
	</div>
	
	<div class="section conteudo">
		<div class="paginas">
			<a id="modelo" class="menu">Modelo</a>
		</div>
		
		<div>
			<a href="?logout"><img
				src="<?php echo DIR ?>img/logout.png"
				alt="Saída"
				title="Sair"
				class="logout"
			/></a>
		</div>
		
		<div id="modelo_oculto" class="paginas">
			<a href="<?php echo DIR ?>main/modelo/visualizacao.php">Visualização</a>
			<a href="<?php echo DIR ?>main/modelo/busca.php">Busca</a>
			<a href="<?php echo DIR ?>main/modelo/cadastro.php">Cadastro</a>
		</div>
	</div>
</div>