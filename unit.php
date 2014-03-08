<?php require_once('mod/Config.php'); new Config(true); ?>

<div>Miti API</div>

<?php
try{
	$MitiUnit=new MitiUnit();
	new MitiBDUnit();
	new MitiDataUnit();
	new MitiDesempenhoUnit();
	new MitiEmailUnit();
	new MitiORMUnit();
	new MitiPaginacaoUnit();
	new MitiStatusUnit();
	new MitiTabelaUnit();
	new MitiTratamentoUnit();
	new MitiValidacaoUnit();
}catch(Exception $e){
	echo $e->getMessage();
}
?>
