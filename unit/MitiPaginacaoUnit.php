<?php
class MitiPaginacaoUnit extends MitiUnit{
	private $MitiPaginacao;
	
	public function __construct(){
		$this->MitiPaginacao=new MitiPaginacao(10,2,3);
		$this->criar();
	}
	
	private function criar(){
		$this->MitiPaginacao->setTotal(100);
		
		$teste='<a href="?pg=1">Primeira</a>';
		$teste.='<a href="?pg=1">Anterior</a>';
		$teste.='<a href="?pg=1">1</a>';
		$teste.='<span class="on">2</span>';
		$teste.='<a href="?pg=3">3</a>';
		$teste.='<a href="?pg=3">Próxima</a>';
		$teste.='<a href="?pg=10">Última</a>';
		
		$this->afirmar($this->MitiPaginacao->criar('?pg=','off','on'),$teste,__METHOD__);
	}
}
?>
