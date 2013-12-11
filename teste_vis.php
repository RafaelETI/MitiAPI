<div class="section">
	<div class="header">Teste > Cadastro</div>
	
	<div class="section conteudo">
		<form method="post" action="" enctype="multipart/form-data">
			<table>
				<tbody>
					<tr>
						<th scope="row"><label for="valor">Valor</label></th>
						<td><input type="text" name="valor" id="valor" maxlength="15" required="required" /></td>
						<td class="acoes" id="valor_auxformcont"></td>
					</tr>

					<tr>
						<th scope="row"><label for="arquivo">Arquivos</label></th>
						<td><input type="file" name="arquivos[]" id="arquivos" multiple="multiple" required="required" /></td>
						<td class="acoes"></td>
					<tr>
				</tbody>
				
				<tfoot><tr><th scope="row" colspan="3"><input type="submit" name="teste_cad" value="Cadastrar" /></th></tr></tfoot>
			</table>
		</form>
	</div>
</div>
