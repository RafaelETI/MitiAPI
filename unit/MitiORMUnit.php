<?php
class MitiORMUnit extends MitiUnit{
	private $MitiORM;
	
	public function __construct(){
		$this->MitiORM=new MitiORM('mitiunit');
		
		//essa ordem importa
		$this->criar();
		$this->ler();
		$this->atualizar();
		$this->ordenar();
		$this->juntar();
		$this->limitar();
		$this->deletar();
	}
	
	private function criar(){
		$this->validarValorVazioException();
		$this->validarTamanhoException();
		
		$id=$this->MitiORM->criar(array('nome'=>'\'Tes\te"','idade'=>'aaa'))->getId();
		$this->MitiORM->definirCampos(array('nome','idade'));
		$teste=$this->MitiORM->ler(array('id'=>array('=',$id)))->obterAssoc();
		$this->afirmar($teste,array('nome'=>'\'Tes\te"','idade'=>'0'),__METHOD__);
	}
	
	private function validarValorVazioException(){
		try{
			$this->MitiORM->criar(array('nome'=>''));
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'Valor vazio',__METHOD__);
		}
	}
	
	private function validarTamanhoException(){
		try{
			$this->MitiORM->criar(array('nome'=>'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaab'));
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'Limite de caractéres excedido',__METHOD__);
		}
	}
	
	private function juntar(){
		$this->MitiORM->setJoins(array('join'));
		$this->MitiORM->setAliases(array('m'));
		$this->MitiORM->setOnTabelas(array('mitiunit'));
		$this->MitiORM->setTabelaChaves(array('id'));
		$this->MitiORM->setTabelasChaves(array('categoria'));
		$this->MitiORM->juntar(array('mitiunit2'));
		
		$this->MitiORM->definirCampos(array('id'),array(array('descricao')));
		$teste=$this->MitiORM->ler(array('id'=>array('=','1')),array(array('descricao'=>array('like','hur'))))->obterAssoc();
		
		$this->afirmar($teste['m_descricao'],'Ben Hur (1959)',__METHOD__);
	}
	
	private function ordenar(){
		$this->MitiORM->definirCampos(array('nome'));
		$this->MitiORM->ordenar(array('id'=>'desc'));
		$teste=$this->MitiORM->ler()->obterAssoc();
		$this->afirmar($teste['nome'],'Teste2',__METHOD__);
	}
	
	private function limitar(){
		$this->MitiORM->criar(array('nome'=>'Teste3'));
		$this->MitiORM->criar(array('nome'=>'Teste4'));
		$this->MitiORM->criar(array('nome'=>'Teste5'));
		$this->MitiORM->limitar(3,1);
		$this->afirmar($this->MitiORM->ler()->obterQuantidade(),2,__METHOD__);
	}
	
	private function ler(){
		$this->MitiORM->definirCampos(array('id'));
		$this->tratarLeituraEscapar();
		$this->tratarLeituraWildcard();
		$this->tratarLeituraSetType();
	}
	
	private function tratarLeituraEscapar(){
		$this->afirmar($this->MitiORM->ler(array('nome'=>array('=','\'Tes\te"')))->obterQuantidade(),1,__METHOD__);
	}
	
	private function tratarLeituraWildcard(){
		$this->afirmar($this->MitiORM->ler(array('nome'=>array('like','es')))->obterQuantidade(),1,__METHOD__);
	}
	
	private function tratarLeituraSetType(){
		$this->afirmar($this->MitiORM->ler(array('idade'=>array('=','tes')))->obterQuantidade(),1,__METHOD__);
	}
	
	private function atualizar(){
		$this->MitiORM->atualizar(array('nome'=>'Teste2','idade'=>''),'2a');
		$this->MitiORM->definirCampos(array('nome','idade'));
		$teste=$this->MitiORM->ler(array('id'=>array('=',2)))->obterAssoc();
		$this->afirmar($teste,array('nome'=>'Teste2','idade'=>null),__METHOD__);
	}
	
	private function deletar(){
		$this->MitiORM->deletar(1);
		$this->MitiORM->definirCampos(array('id'));
		$this->afirmar($this->MitiORM->ler(array('id'=>array('=',1)))->obterQuantidade(),0,__METHOD__);
	}
}
?>
