<div class="section">
	<div class="header">
		<div>
			<?php echo SISTEMA; ?>
			
			<span id="status">
				<?php
				$MitiStatus=new MitiStatus();
				echo $MitiStatus->obterMensagem();
				?>
			</span>
		</div>
		
		<div>Login</div>
	</div>
	
	<div class="section conteudo">
		<form method="post" action="" class="pequeno">
			<table>
				<tbody>
					<tr>
						<th scope="row"><label for="usuario">Usu�rio</label></th>
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
	</div>
</div>
