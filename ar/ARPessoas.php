<?php
class ARPessoas extends AR{
	protected $tabela='pessoas';
	
	protected $tipos=array(
		'id'=>'number',
		'nome'=>'string',
		'sexo'=>'number',
		'estado'=>'number'
	);
	
	protected $anulaveis=array(
		'id'=>false,
		'nome'=>false,
		'sexo'=>false,
		'estado'=>false
	);
	
	protected $tamanhos=array(
		'id'=>3,
		'nome'=>100,
		'sexo'=>3,
		'estado'=>3
	);
	
	protected $pk='id';
}
?>
