<?php
try{
	$MitiCRUD=new MitiCRUD(new ARPessoas());
	//$MitiCRUD->deletar(4);
	//$MitiCRUD->inserir(array('nome'=>'Judith','sexo'=>1));
	//$MitiCRUD->alterar(array('nome'=>'Mark'),4);
	$MitiCRUD->juntar(new ARSexos(),'sexo','id');
	$MitiCRUD->definirCampos(array('id','nome'),array('nome'));
	$MitiBD=$MitiCRUD->ler();
	
	while(($pessoas=$MitiBD->obterAssoc())==true){
		echo $pessoas['id'].' | '.$pessoas['nome'].' | '.$pessoas['sexos_nome'].'<br />';
	}
}catch(Exception $e){
	echo $e->getMessage();
}
?>

<div class="section">
	<div class="header">Modelo > Visualização</div>
	
	<div class="section conteudo">
		<table class="lista">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Coluna</th>
					<th scope="col">Coluna 2</th>
					<th scope="col">Coluna 3</th>
					<th scope="col">Ações</th>
				</tr>
			</thead>
			
			<tbody>
				<tr class="par">
					<th scope="row">1</th>
					<td title="Curabitur id justo mattis, euismod.">Curabitu...</td>
					
					<td title="Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin. ">
						Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin.
					</td>
					
					<td title="498.9">498.9</td>
					
					<td title="" class="acoes">
						<form method="get" action="">
							<input type="hidden" name="arquivo" value="modelo_ce" />
							<input type="hidden" name="id" value="1" />
							<input type="submit" value="E" title="Editar" />
						</form>
						
						<form method="post" action="" id="modelo_del">
							<input type="hidden" name="id" value="1" />
							<input type="submit" name="modelo_del" value="D" title="Deletar" />
						</form>
					</td>
				</tr>
				
				<tr class="impar">
					<th scope="row">1</th>
					<td title="Curabitur id justo mattis, euismod.">Curabitu...</td>
					
					<td title="Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin. ">
						Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin.
					</td>
					
					<td title="498.9">498.9</td>
					
					<td title="" class="acoes">
						<form method="get" action="">
							<input type="hidden" name="arquivo" value="modelo_ce" />
							<input type="hidden" name="id" value="1" />
							<input type="submit" value="E" title="Editar" />
						</form>
						
						<form method="post" action="" id="modelo_del">
							<input type="hidden" name="id" value="1" />
							<input type="submit" name="modelo_del" value="D" title="Deletar" />
						</form>
					</td>
				</tr>
				
				<tr class="par">
					<th scope="row">1</th>
					<td title="Curabitur id justo mattis, euismod.">Curabitu...</td>
					
					<td title="Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin. ">
						Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin.
					</td>
					
					<td title="498.9">498.9</td>
					
					<td title="" class="acoes">
						<form method="get" action="">
							<input type="hidden" name="arquivo" value="modelo_ce" />
							<input type="hidden" name="id" value="1" />
							<input type="submit" value="E" title="Editar" />
						</form>
						
						<form method="post" action="" id="modelo_del">
							<input type="hidden" name="id" value="1" />
							<input type="submit" name="modelo_del" value="D" title="Deletar" />
						</form>
					</td>
				</tr>
				
				<tr class="impar">
					<th scope="row">1</th>
					<td title="Curabitur id justo mattis, euismod.">Curabitu...</td>
					
					<td title="Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin. ">
						Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin.
					</td>
					
					<td title="498.9">498.9</td>
					
					<td title="" class="acoes">
						<form method="get" action="">
							<input type="hidden" name="arquivo" value="modelo_ce" />
							<input type="hidden" name="id" value="1" />
							<input type="submit" value="E" title="Editar" />
						</form>
						
						<form method="post" action="" id="modelo_del">
							<input type="hidden" name="id" value="1" />
							<input type="submit" name="modelo_del" value="D" title="Deletar" />
						</form>
					</td>
				</tr>
				
				<tr class="par">
					<th scope="row">1</th>
					<td title="Curabitur id justo mattis, euismod.">Curabitu...</td>
					
					<td title="Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin. ">
						Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin.
					</td>
					
					<td title="498.9">498.9</td>
					
					<td title="" class="acoes">
						<form method="get" action="">
							<input type="hidden" name="arquivo" value="modelo_ce" />
							<input type="hidden" name="id" value="1" />
							<input type="submit" value="E" title="Editar" />
						</form>
						
						<form method="post" action="" id="modelo_del">
							<input type="hidden" name="id" value="1" />
							<input type="submit" name="modelo_del" value="D" title="Deletar" />
						</form>
					</td>
				</tr>
				
				<tr class="impar">
					<th scope="row">1</th>
					<td title="Curabitur id justo mattis, euismod.">Curabitu...</td>
					
					<td title="Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin. ">
						Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin.
					</td>
					
					<td title="498.9">498.9</td>
					
					<td title="" class="acoes">
						<form method="get" action="">
							<input type="hidden" name="arquivo" value="modelo_ce" />
							<input type="hidden" name="id" value="1" />
							<input type="submit" value="E" title="Editar" />
						</form>
						
						<form method="post" action="" id="modelo_del">
							<input type="hidden" name="id" value="1" />
							<input type="submit" name="modelo_del" value="D" title="Deletar" />
						</form>
					</td>
				</tr>
				
				<tr class="par">
					<th scope="row">1</th>
					<td title="Curabitur id justo mattis, euismod.">Curabitu...</td>
					
					<td title="Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin. ">
						Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin.
					</td>
					
					<td title="498.9">498.9</td>
					
					<td title="" class="acoes">
						<form method="get" action="">
							<input type="hidden" name="arquivo" value="modelo_ce" />
							<input type="hidden" name="id" value="1" />
							<input type="submit" value="E" title="Editar" />
						</form>
						
						<form method="post" action="" id="modelo_del">
							<input type="hidden" name="id" value="1" />
							<input type="submit" name="modelo_del" value="D" title="Deletar" />
						</form>
					</td>
				</tr>
				
				<tr class="impar">
					<th scope="row">1</th>
					<td title="Curabitur id justo mattis, euismod.">Curabitu...</td>
					
					<td title="Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin. ">
						Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin.
					</td>
					
					<td title="498.9">498.9</td>
					
					<td title="" class="acoes">
						<form method="get" action="">
							<input type="hidden" name="arquivo" value="modelo_ce" />
							<input type="hidden" name="id" value="1" />
							<input type="submit" value="E" title="Editar" />
						</form>
						
						<form method="post" action="" id="modelo_del">
							<input type="hidden" name="id" value="1" />
							<input type="submit" name="modelo_del" value="D" title="Deletar" />
						</form>
					</td>
				</tr>
				
				<tr class="par">
					<th scope="row">1</th>
					<td title="Curabitur id justo mattis, euismod.">Curabitu...</td>
					
					<td title="Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin. ">
						Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin.
					</td>
					
					<td title="498.9">498.9</td>
					
					<td title="" class="acoes">
						<form method="get" action="">
							<input type="hidden" name="arquivo" value="modelo_ce" />
							<input type="hidden" name="id" value="1" />
							<input type="submit" value="E" title="Editar" />
						</form>
						
						<form method="post" action="" id="modelo_del">
							<input type="hidden" name="id" value="1" />
							<input type="submit" name="modelo_del" value="D" title="Deletar" />
						</form>
					</td>
				</tr>
				
				<tr class="impar">
					<th scope="row">1</th>
					<td title="Curabitur id justo mattis, euismod.">Curabitu...</td>
					
					<td title="Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin. ">
						Proin tincidunt lorem tortor, ut cursus urna convallis non. Proin.
					</td>
					
					<td title="498.9">498.9</td>
					
					<td title="" class="acoes">
						<form method="get" action="">
							<input type="hidden" name="arquivo" value="modelo_ce" />
							<input type="hidden" name="id" value="1" />
							<input type="submit" value="E" title="Editar" />
						</form>
						
						<form method="post" action="" id="modelo_del">
							<input type="hidden" name="id" value="1" />
							<input type="submit" name="modelo_del" value="D" title="Deletar" />
						</form>
					</td>
				</tr>
			</tbody>
			
			<tfoot>
				<tr>
					<td colspan="5">
						<div class="esquerda">
							<form method="get" action="">
								<input type="hidden" name="arquivo" value="modelo_vis" />
								<input type="hidden" name="mais" value="1" />
								<input type="submit" value="Ver todos" />
							</form>
						
							<span>Mostrando 10 de 497 registros</span>
						</div>
						
						<div class="direita">Tempo da requisição: 0.0065 seg.</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
