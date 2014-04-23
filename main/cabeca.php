<?php $MitiTratamento=new MitiTratamento ?>

<meta charset="windows-1252" />
<meta name="author" content="Rafael Barros" />

<title><?php echo SISTEMA ?></title>

<link rel="shortcut icon" href="<?php echo RAIZ ?>img/fav.png" type="image/png" />

<link rel="stylesheet" href="<?php echo RAIZ ?>css/geral.css" />

<script src="<?php echo RAIZ ?>lib/js/jquery_min.js"></script>
<script src="<?php echo RAIZ ?>lib/js/miti/MitiPadrao.js"></script>
<script src="<?php echo RAIZ ?>lib/js/miti/MitiElemento.js"></script>
<script src="<?php echo RAIZ ?>lib/js/miti/MitiFormulario.js"></script>
<script src="<?php echo RAIZ ?>js/Geral.js"></script>

<?php
$MitiStatus=new MitiStatus;
echo $MitiStatus->obterAlerta();