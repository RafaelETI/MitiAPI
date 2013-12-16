<?php
if(isset($_GET['id'])==false){
	$header='Cadastro';
	$name='modelo_cad';
	$value='Cadastrar';
}else{
	$header='Edi��o';
	$name='modelo_edi';
	$value='Editar';
}
?>
<div class="section">
	<div class="header">Modelo > <?php echo $header; ?></div>
	
	<div class="section conteudo">
		<form method="post" action="" enctype="multipart/form-data">
			<table>
				<tbody>
					<tr>
						<th scope="row"><label for="valor">Valor</label></th>
						<td><input type="text" name="valor" id="valor" maxlength="15" required="required" /></td>
						<td id="valor_auxformcont"></td>
					</tr>
					
					<tr>
						<th scope="row">Valor 2</th>
						
						<td>
							<label for="valor2_a">A</label>
							<input type="radio" name="valor2" id="valor2_a" value="1" checked="checked" />
							
							<label for="valor2_b">B</label>
							<input type="radio" name="valor2" id="valor2_b" value="2" />
						</td>
						
						<td id="valor2_auxformcont"></td>
					</tr>
					
					<tr>
						<th scope="row">Valores 3</th>
						
						<td>
							<label for="valor3_c">C</label>
							<input type="checkbox" name="valor3" id="valor3_c" value="1" />
							
							<label for="valor3_d">D</label>
							<input type="checkbox" name="valor3" id="valor3_d" value="2" />
						</td>
						
						<td id="valor3_auxformcont"></td>
					</tr>
					
					<tr>
						<th scope="row"><label for="valor4">Valor 4</label></th>
						
						<td>
							<select name="valor4" id="valor4" required="required">
								<option value=""></option>
								<option value="1">�tem 1</option>
								<option value="2">�tem 2</option>
								<option value="3">�tem 3</option>
							</select>
						</td>
						
						<td id="valor4_auxformcont"></td>
					</tr>

					<tr>
						<th scope="row"><label for="arquivos">Arquivos</label></th>
						<td><input type="file" name="arquivos[]" id="arquivos" multiple="multiple" required="required" /></td>
						<td id="valor4_auxformcont"></td>
					<tr>
					
					<tr>
						<th scope="row"><label for="valor5">Valor 5</label></th>
						<td><textarea name="valor5" id="valor5" maxlength="500" required="required"></textarea></td>
						<td id="valor5_auxformcont"></td>
					</tr>
				</tbody>
				
				<tfoot>
					<tr>
						<td colspan="3">
							<input type="submit" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
						</td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>
