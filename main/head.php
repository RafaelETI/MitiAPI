<?php $MitiTratamento=new MitiTratamento ?>

<title><?php echo SISTEMA ?></title>
<meta name="author" content="Rafael Barros" />
<link rel="shortcut icon" href="<?php echo RAIZ ?>img/fav.png" type="image/png" />

<?php
echo $MitiTratamento->requerer(RAIZ.'css/geral.css');
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?php
echo $MitiTratamento->requerer(RAIZ.'lib/js/miti/MitiPadrao.js');
echo $MitiTratamento->requerer(RAIZ.'lib/js/miti/MitiElemento.js');
echo $MitiTratamento->requerer(RAIZ.'lib/js/miti/MitiFormulario.js');
echo $MitiTratamento->requerer(RAIZ.'js/Geral.js');
?>
<script>
var MitiPadrao=new MitiPadrao;
var MitiElemento=new MitiElemento;
var MitiFormulario=new MitiFormulario('#007E7A');

var Geral=new Geral;
MitiPadrao.iniciar(function(){Geral.enfeitar();});
</script>

<?php
$MitiStatus=new MitiStatus;
echo $MitiStatus->obterAlerta();
