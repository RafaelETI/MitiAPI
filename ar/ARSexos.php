<?php
class ARSexos extends AR{
	protected $tabela='sexos';
	
	protected $campos=array(
		0=>'id',
		1=>'nome'
	);
	
	protected $tipos=array(
		0=>'number',
		1=>'string'
	);
	
	protected $anulaveis=array(
		0=>false,
		1=>false
	);
	
	protected $tamanhos=array(
		0=>3,
		1=>10
	);
	
	protected $pk=0;
}
?>
