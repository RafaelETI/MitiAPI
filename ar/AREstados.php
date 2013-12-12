<?php
class AREstados extends AR{
	protected $tabela='estados';
	
	protected $tipos=array(
		'id'=>'number',
		'nome'=>'string'
	);
	
	protected $anulaveis=array(
		'id'=>false,
		'nome'=>false
	);
	
	protected $tamanhos=array(
		'id'=>3,
		'nome'=>20
	);
	
	protected $pk='id';
}
?>
