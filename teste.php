<script src="js/teste.js"></script>

<section>
	<header>Teste</header>
	
	<section class="conteudo">
		<form method="post" action="" enctype="multipart/form-data">
			<table>
				<tbody>
					<tr>
						<th scope="row"><label for="valor">Valor</label></th>
						<td><input type="text" name="valor" id="valor" value="" maxlength="15" /></td>
						<td class="acoes" id="valor_auxformcont"></td>
					</tr>
					
					<tr>
						<th scope="row"><label for="valor2">Valor 2</label></th>
						<td><input type="text" name="valor2" id="valor2" value="" maxlength="20" /></td>
						<td class="acoes" id="valor2_auxformcont"></td>
					<tr>
					
					<tr>
						<th scope="row"><label for="valor3">Valor 3</label></th>
						<td><input type="text" name="valor3" id="valor3" value="" maxlength="10" /></td>
						<td class="acoes" id="valor3_auxformcont"></td>
					<tr>
					
					<tr>
						<th scope="row"><label for="arquivo">Arquivos</label></th>
						<td><input type="file" name="arquivos[]" id="arquivos" multiple="multiple" /></td>
						<td class="acoes"></td>
					<tr>
					
					<tr>
						<th scope="row">Resultado</th>
						<td>
							<?php
							if(isset($_POST['testar'])==true){
								try{
									//$MitiTratamento=new MitiTratamento();
									//$MitiParcialidade=new MitiParcialidade();
									//$MitiParcialidade->setExclusivos(array($_POST['valor'],$_POST['valor3']));
									//$MitiTratamento->encurtar($_POST);
									//$MitiTratamento->removerAcentos($_POST);
									
									//$MitiAssinatura=new MitiAssinatura();
									//$MitiParcialidade=new MitiParcialidade();
									//$MitiParcialidade->setExclusivos(array($_POST['valor'],$_POST['valor3']));
									//$MitiAssinatura->htmlSpecialChars($_POST);
									
									//$MitiValidacao=new MitiValidacao();
									//$MitiParcialidade=new MitiParcialidade();
									//$MitiParcialidade->setExclusivos(array($_POST['valor'],$_POST['valor3']));
									//$MitiValidacao->upload('arquivos','jpeg',3000000,true,1.2,1.9);
									//$MitiValidacao->cnpj($_POST['valor']);
									//$MitiValidacao->cpf($_POST['valor']);
									//$MitiValidacao->email($_POST['valor']);
									//$MitiValidacao->tamanho($_POST['valor'],5);
									//$MitiValidacao->vazio($_POST['valor']);
									
									//$MitiBD=new MitiBD('localhost','root','','test');
									//$MitiParcialidade=new MitiParcialidade();
									//$MitiParcialidade->setExclusivos(array($_POST['valor'],$_POST['valor3']));
									//$MitiBD->escapar($_POST);
									//$MitiBD->requisitar('insert into teste(nome)values("Alice")');
									
									//$MitiData=new MitiData();
									//$MitiData->inverter($_POST['valor']);
									
									echo '<pre>';
									print_r($_POST);
									echo '</pre>';
								}catch(Exception $e){
									echo $e->getMessage();
								}
							}
							?>
						</td>
						<td class="acoes"></td>
					<tr>
				</tbody>
				
				<tfoot><tr><th scope="row" colspan="3"><input type="submit" name="testar" value="Testar" /></th></tr></tfoot>
			</table>
		</form>
	</section>
</section>
