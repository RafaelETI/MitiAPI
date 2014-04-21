<?php
require_once '../adt/Config.php';
new Config(false,'../');
?>
<!doctype html>
<html lang="pt-br">
<head>
<?php require_once '../main/cabeca.php' ?>
<script src="../js/Login.js"></script>
</head>
<!--==========neck==========-->
<body>
<div id="geral">
<div class="section">
	<div class="header">
		<div><?php echo SISTEMA ?></div>
		<div class="ultimo_filho">Login</div>
	</div>
	
	<div class="section conteudo">
		<form method="post" action="" class="pequeno">
			<table>
				<tbody>
					<tr>
						<th scope="row"><label for="usuario">Usuário</label></th>
						
						<td>
							<input
								type="text"
								name="usuario"
								id="usuario"
								required="required"
							/>
						</td>
						
						<td class="ultimo_filho"></td>
					</tr>
					
					<tr>
						<th scope="row"><label for="usuario">Senha</label></th>
						
						<td>
							<input
								type="password"
								name="senha"
								id="senha"
								required="required"
							/>
						</td>
						
						<td class="ultimo_filho"></td>
					<tr>
				</tbody>
				
				<tfoot>
					<tr>
						<th scope="row" colspan="3">
							<input type="submit" name="login" value="Entrar" />
						</th>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>
</div>
</body>
</html>