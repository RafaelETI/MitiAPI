<?php
class ARSessao extends AR{
	protected $tabela='sessao';
	
	protected $campos=array(
		0=>'usuario',
		1=>'senha'
	);
	
	protected $tipos=array(
		0=>'string',
		1=>'string'
	);
	
	protected $anulaveis=array(
		0=>false,
		1=>false
	);
	
	protected $tamanhos=array(
		0=>5,
		1=>34
	);
	
	protected $pk=0;
}
?>
