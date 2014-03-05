<?php require_once('mod/Config.php'); new Config(true); ?>

<div>Miti Framework</div>

<?php
$MitiUnit=new MitiUnit();
new MitiBDUnit();
new MitiCRUDUnit();
new MitiDataUnit();
new MitiDesempenhoUnit();
new MitiEmailUnit();
new MitiPaginacaoUnit();
new MitiStatusUnit();
new MitiTabelaUnit();
new MitiTratamentoUnit();
new MitiValidacaoUnit();
$MitiUnit->removerTabelas();
?>
