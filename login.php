<script src="js/login.js"></script>

<section>
	<header>
		<div class="esquerda">
			<?php echo SISTEMA; ?>
			
			<span id="status">
				<?php
				$MitiStatus=new MitiStatus();
				echo $MitiStatus->obterMensagem();
				?>
			</span>
		</div>
		
		<div class="direita">Login</div>
	</header>
	
	<section class="conteudo">
		<form method="post" action="" class="pequeno">
			<table>
				<tbody>
					<tr>
						<th scope="row"><label for="usuario">Usuário</label></th>
						<td><input type="text" name="usuario" id="usuario" required="required" /></td>
						<td class="acoes"></td>
					</tr>
					
					<tr>
						<th scope="row"><label for="usuario">Senha</label></th>
						<td><input type="password" name="senha" id="senha" required="required" /></td>
						<td class="acoes"></td>
					<tr>
				</tbody>
				
				<tfoot><tr><th scope="row" colspan="3"><input type="submit" name="login" value="Entrar" /></th></tr></tfoot>
			</table>
		</form>
	</section>
</section>
