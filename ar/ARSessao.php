<?php
class ARSessao extends AR{
	protected $tabela='sessao';
	
	protected $tipos=array(
		'usuario'=>'string',
		'senha'=>'string'
	);
	
	protected $anulaveis=array(
		'usuario'=>false,
		'senha'=>false
	);
	
	protected $tamanhos=array(
		'usuario'=>5,
		'senha'=>34
	);
	
	protected $pk='usuario';
}
?>
