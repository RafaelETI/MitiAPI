<?php
class MitiCRUDUnit extends MitiUnit{
	private $MitiCRUD;

	public function __construct(){
		$this->MitiCRUD=new MitiCRUD('mitiunit');
		
		$this->criar();
		$this->ler();
		$this->atualizar();
		$this->ordenar();
		$this->juntar();
		$this->limitar();
		$this->deletar();
	}
	
	private function validarValorVazio(){
		try{$this->MitiCRUD->criar(array('nome'=>''));}
		catch(Exception $e){$this->afirmar($e->getMessage(),'Valor vazio',__METHOD__);}
	}
	
	private function validarTamanho(){
		try{$this->MitiCRUD->criar(array('nome'=>'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaab'));}
		catch(Exception $e){$this->afirmar($e->getMessage(),'Limite de caractéres excedido',__METHOD__);}
	}
	
	private function criar(){
		$this->validarValorVazio();
		$this->validarTamanho();
		
		$id=$this->MitiCRUD->criar(array('nome'=>'\'Tes\te"','idade'=>'aaa'))->getId();
		$this->MitiCRUD->definirCampos(array('nome','idade'));
		$teste=$this->MitiCRUD->ler(array('id'=>array('=',$id)))->obterAssoc();
		$this->afirmar($teste,array('nome'=>'\'Tes\te"','idade'=>'0'),__METHOD__);
	}
	
	private function tratarLeituraEscapar(){
		$this->afirmar($this->MitiCRUD->ler(array('nome'=>array('=','\'Tes\te"')))->obterQuantidade(),1,__METHOD__);
	}
	
	private function tratarLeituraWildcard(){
		$this->afirmar($this->MitiCRUD->ler(array('nome'=>array('like','es')))->obterQuantidade(),1,__METHOD__);
	}
	
	private function tratarLeituraSetType(){
		$this->afirmar($this->MitiCRUD->ler(array('idade'=>array('=','tes')))->obterQuantidade(),1,__METHOD__);
	}
	
	private function ler(){
		$this->MitiCRUD->definirCampos(array('id'));
		$this->tratarLeituraEscapar();
		$this->tratarLeituraWildcard();
		$this->tratarLeituraSetType();
	}
	
	private function atualizar(){
		$this->MitiCRUD->atualizar(array('nome'=>'Teste2','idade'=>''),'2a');
		$this->MitiCRUD->definirCampos(array('nome','idade'));
		$teste=$this->MitiCRUD->ler(array('id'=>array('=',2)))->obterAssoc();
		$this->afirmar($teste,array('nome'=>'Teste2','idade'=>null),__METHOD__);
	}
	
	private function ordenar(){
		$this->MitiCRUD->definirCampos(array('nome'));
		$this->MitiCRUD->ordenar(array('id'=>'desc'));
		$teste=$this->MitiCRUD->ler()->obterAssoc();
		$this->afirmar($teste['nome'],'Teste2',__METHOD__);
	}
	
	private function juntar(){
		$this->MitiCRUD->setJoins(array('join'));
		$this->MitiCRUD->setAliases(array('m'));
		$this->MitiCRUD->setOnTabelas(array('mitiunit'));
		$this->MitiCRUD->setTabelaChaves(array('id'));
		$this->MitiCRUD->setTabelasChaves(array('categoria'));
		$this->MitiCRUD->juntar(array('mitiunit2'));
		$this->MitiCRUD->definirCampos(array('id'),array(array('descricao')));
		$teste=$this->MitiCRUD->ler()->obterAssoc();
		$this->afirmar($teste['m_descricao'],'Ben Hur (1959)',__METHOD__);
	}
	
	private function limitar(){
		$this->MitiCRUD->criar(array('nome'=>'Teste3'));
		$this->MitiCRUD->criar(array('nome'=>'Teste4'));
		$this->MitiCRUD->criar(array('nome'=>'Teste5'));
		$this->MitiCRUD->limitar(3,1);
		$this->afirmar($this->MitiCRUD->ler()->obterQuantidade(),2,'MitiCRUD::limitar()');
	}
	
	private function deletar(){
		$this->MitiCRUD->deletar(1);
		$this->MitiCRUD->definirCampos(array('id'));
		$this->afirmar($this->MitiCRUD->ler(array('id'=>array('=',1)))->obterQuantidade(),0,__METHOD__);
	}
}
?>
