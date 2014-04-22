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
			<a href="../login.php?acao=logout"><img
				src="<?php echo RAIZ ?>img/logout.png"
				alt="Saída"
				title="Sair"
				class="logout"
			/></a>
		</div>
		
		<div id="modelo_oculto" class="paginas">
			<a href="<?php echo RAIZ ?>main/modelo/visualizacao.php">Visualização</a>
			<a href="<?php echo RAIZ ?>main/modelo/busca.php">Busca</a>
			<a href="<?php echo RAIZ ?>main/modelo/cadastro.php">Cadastro</a>
		</div>
	</div>
</div>