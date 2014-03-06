<?php
class MitiValidacaoUnit extends MitiUnit{
	private $MitiValidacao;
	
	public function __construct(){
		$this->MitiValidacao=new MitiValidacao();
		
		$this->tamanho();
		$this->tamanhoException();
		$this->email();
		$this->emailException();
		$this->vazioString();
		$this->vazioStringException();
		$this->vazioArrayException();
		$this->vazioArray();
		$this->upload();
		$this->uploadImagem();
		$this->cpf();
		$this->cnpj();
	}
	
	private function tamanho(){
		$teste='teste';
		$this->MitiValidacao->tamanho($teste,5);
		$this->afirmar($teste,$teste,__METHOD__);
	}
	
	private function tamanhoException(){
		$teste='testes';
		
		try{
			$this->MitiValidacao->tamanho($teste,5);
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'O valor deve conter 5 caractéres',__METHOD__);
		}
	}
	
	private function email(){
		$teste='conta@dominio.com';
		$this->MitiValidacao->email($teste);
		$this->afirmar($teste,$teste,__METHOD__);
	}
	
	private function emailException(){
		$teste='conta(at)dominio.com';
		
		try{
			$this->MitiValidacao->email($teste);
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'O e-mail é inválido',__METHOD__);
		}
	}
	
	private function vazioString(){
		$teste='a';
		$this->MitiValidacao->vazio($teste);
		$this->afirmar($teste,$teste,__METHOD__);
	}
	
	private function vazioStringException(){
		$teste='';
		
		try{
			$this->MitiValidacao->vazio($teste);
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'Valor vazio',__METHOD__);
		}
	}
	
	private function vazioArray(){
		$teste=array('a','b','c');
		$this->MitiValidacao->vazio($teste);
		$this->afirmar($teste,$teste,__METHOD__);
	}
	
	private function vazioArrayException(){
		$teste=array('a','','c');
		
		try{
			$this->MitiValidacao->vazio($teste);
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'Valor vazio',__METHOD__);
		}
	}
	
	private function upload(){
		$this->declararFiles();
		
		$this->MitiValidacao->upload('arquivo',2048,array('jpeg','png','gif'));
		$this->afirmar($_FILES['arquivo']['name'],$_FILES['arquivo']['name'],__METHOD__);
	}
	
	private function uploadImagem(){
		$this->declararFiles();
	
		$this->MitiValidacao->uploadImagem('arquivo',16,16);
		$this->afirmar($_FILES['arquivo']['name'],$_FILES['arquivo']['name'],__METHOD__);
	}
	
	private function declararFiles(){
		$_FILES['arquivo']['name'][0]='mitiunit.png';
		$_FILES['arquivo']['type'][0]='image/png';
		$_FILES['arquivo']['tmp_name'][0]=RAIZ.'img/mitiunit.png';
		$_FILES['arquivo']['size'][0]='1457';
	}
	
	private function cpf(){
		$teste='11550994603';
		$this->MitiValidacao->cpf($teste);
		$this->afirmar($teste,$teste,__METHOD__);
	}
	
	private function cnpj(){
		$teste='87210343000169';
		$this->MitiValidacao->cnpj($teste);
		$this->afirmar($teste,$teste,__METHOD__);
	}
}
?>
