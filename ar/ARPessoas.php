<?php
class ARPessoas extends AR{
	protected $tabela='pessoas';
	
	protected $campos=array(
		0=>'id',
		1=>'nome',
		2=>'sexo'
	);
	
	protected $tipos=array(
		0=>'number',
		1=>'string',
		2=>'number'
	);
	
	protected $anulaveis=array(
		0=>false,
		1=>false,
		2=>false
	);
	
	protected $tamanhos=array(
		0=>3,
		1=>100,
		2=>3
	);
	
	protected $pk=0;
}
?>
