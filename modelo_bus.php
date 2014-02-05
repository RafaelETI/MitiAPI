<?php require_once('mod/Config.php'); new Config(true); ?>
<!doctype html>
<html lang="pt-br">
<head>
<?php require_once('cabeca.php'); ?>

<script src="js/modelo_bus.js"></script>
</head>
<!--==========neck==========-->
<body>
<div id="geral">
<?php require_once('menu.php'); ?>

<div class="section">
	<div class="header">Modelo &gt; Busca</div>
	
	<div class="section conteudo">
		<form method="get" action="modelo_vis.php" class="busca">
			<input type="hidden" name="mais" value="1" />
			
			<table>
				<tbody>
					<tr>
						<th scope="row"><label for="valor">Valor</label></th>
						
						<td>
							<select name="op" id="op">
								<option value="like">like</option>
								<option value="=">=</option>
								<option value="<">&lt;</option>
								<option value=">">&gt;</option>
								<option value="!=">!=</option>
							</select>
							
							<input type="text" name="valor" id="valor" maxlength="15" />
						</td>
						
						<td class="ultimo_filho" id="valor_auxformcont"></td>
					</tr>
					
					<tr>
						<th scope="row"><label for="valor2">Valor 2</label></th>
						
						<td>
							<select name="op2" id="op2">
								<option value="=">=</option>
								<option value="like">like</option>
								<option value="<">&lt;</option>
								<option value=">">&gt;</option>
								<option value="!=">!=</option>
							</select>
						
							<select name="valor2" id="valor2">
								<option value=""></option>
								<option value="1">Ítem 1</option>
								<option value="2">Ítem 2</option>
								<option value="3">Ítem 3</option>
							</select>
						</td>
						
						<td class="ultimo_filho" id="valor2_auxformcont"></td>
					</tr>
				</tbody>
				
				<tfoot>
					<tr>
						<td colspan="3">
							<input type="submit" value="Buscar" />
						</td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>
</div>
</body>
</html>
