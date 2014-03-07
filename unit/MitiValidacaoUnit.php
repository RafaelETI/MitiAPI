<?php
class MitiValidacaoUnit extends MitiUnit{
	private $MitiValidacao;
	
	public function __construct(){
		$this->MitiValidacao=new MitiValidacao();
		
		$this->tamanho();
		$this->email();
		$this->vazioArray();
		$this->vazioScalar();
		$this->upload();
		$this->uploadImagem();
		$this->CPF();
		$this->CNPJ();
	}
	
	private function tamanho(){
		$teste='teste';
		$this->MitiValidacao->tamanho($teste,5);
		$this->afirmar(true,true,__METHOD__);
		
		$this->tamanhoException();
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
		$this->afirmar(true,true,__METHOD__);
		
		$this->emailException();
	}
	
	private function emailException(){
		$teste='conta(at)dominio.com';
		
		try{
			$this->MitiValidacao->email($teste);
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'O e-mail é inválido',__METHOD__);
		}
	}
	
	private function vazioArray(){
		$teste=array('a','b','c');
		$this->MitiValidacao->vazio($teste);
		$this->afirmar(true,true,__METHOD__);
		
		$this->vazioArrayException();
	}
	
	private function vazioArrayException(){
		$teste=array('a','','c');
		
		try{
			$this->MitiValidacao->vazio($teste);
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'Valor vazio',__METHOD__);
		}
	}
	
	private function vazioScalar(){
		$teste='a';
		$this->MitiValidacao->vazio($teste);
		$this->afirmar(true,true,__METHOD__);
		
		$this->vazioScalarException();
	}
	
	private function vazioScalarException(){
		$teste='';
		
		try{
			$this->MitiValidacao->vazio($teste);
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'Valor vazio',__METHOD__);
		}
	}
	
	private function upload(){
		$this->declararFiles();
		
		$this->MitiValidacao->upload('arquivo',2048,array('jpeg','png','gif'));
		$this->afirmar(true,true,__METHOD__);
		
		$this->validarPesoException();
		$this->validarTiposException();
	}
	
	private function validarPesoException(){
		try{
			$this->MitiValidacao->upload('arquivo',1024,array('jpeg','png','gif'));
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'O arquivo excede o tamanho permitido',__METHOD__);
		}
	}
	
	private function validarTiposException(){
		try{
			$this->MitiValidacao->upload('arquivo',2048,array('doc','pdf','xls'));
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'O tipo do arquivo é inválido',__METHOD__);
		}
	}
	
	private function uploadImagem(){
		$this->MitiValidacao->uploadImagem('arquivo',16,16);
		$this->afirmar(true,true,__METHOD__);
		
		$this->validarTamanhoLarguraException();
		$this->validarTamanhoAlturaException();
		$this->validarProporcoesVerticalException();
		$this->validarProporcoesHorizontalException();
	}
	
	private function validarTamanhoLarguraException(){
		try{
			$this->MitiValidacao->uploadImagem('arquivo',20,16);
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'A largura da imagem é menor do que o mínimo permitido',__METHOD__);
		}
	}
	
	private function validarTamanhoAlturaException(){
		try{
			$this->MitiValidacao->uploadImagem('arquivo',16,20);
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'A altura da imagem é menor do que o mínimo permitido',__METHOD__);
		}
	}
	
	private function validarProporcoesVerticalException(){
		try{
			$this->MitiValidacao->uploadImagem('arquivo',16,8);
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'A proporção da imagem é inválida, excedendo verticalmente',__METHOD__);
		}
	}
	
	private function validarProporcoesHorizontalException(){
		try{
			$this->MitiValidacao->uploadImagem('arquivo',8,16);
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'A proporção da imagem é inválida, excedendo horizontalmente',__METHOD__);
		}
	}
	
	private function declararFiles(){
		$_FILES['arquivo']['name'][0]='mitiunit.png';
		$_FILES['arquivo']['type'][0]='image/png';
		$_FILES['arquivo']['tmp_name'][0]=RAIZ.'img/mitiunit.png';
		$_FILES['arquivo']['size'][0]='1457';
	}
	
	private function CPF(){
		$teste='27981094003';
		$this->MitiValidacao->CPF($teste);
		$this->afirmar(true,true,__METHOD__);
		
		$this->validarQuantidadeCaracteresException();
		$this->validarApenasNumerosException();
		$this->validarSequenciaIgualException();
		$this->validarDigitosCPFException();
	}
	
	private function validarQuantidadeCaracteresException(){
		try{
			$this->MitiValidacao->CPF('279810940033');
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'#1 - O CPF é inválido',__METHOD__);
		}
	}
	
	private function validarApenasNumerosException(){
		try{
			$this->MitiValidacao->CPF('279810a4003');
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'#2 - O CPF é inválido',__METHOD__);
		}
	}
	
	private function validarSequenciaIgualException(){
		try{
			$this->MitiValidacao->CPF('88888888888');
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'#3 - O CPF é inválido',__METHOD__);
		}
	}
	
	private function validarDigitosCPFException(){
		try{
			$this->MitiValidacao->CPF('27981094004');
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'#4 - O CPF é inválido',__METHOD__);
		}
	}
	
	private function CNPJ(){
		$teste='87210343000169';
		$this->MitiValidacao->CNPJ($teste);
		$this->afirmar(true,true,__METHOD__);
		
		$this->validarQuantidadeCaracteresCNPJException();
		$this->validarApenasNumerosCNPJException();
		$this->validarSequenciaZerosException();
		$this->validarDigitosCNPJException();
	}
	
	private function validarQuantidadeCaracteresCNPJException(){
		try{
			$this->MitiValidacao->CNPJ('872103430001699');
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'#1 - O CNPJ é inválido',__METHOD__);
		}
	}
	
	private function validarApenasNumerosCNPJException(){
		try{
			$this->MitiValidacao->CNPJ('87210343a00169');
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'#2 - O CNPJ é inválido',__METHOD__);
		}
	}
	
	private function validarSequenciaZerosException(){
		try{
			$this->MitiValidacao->CNPJ('00000000000000');
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'#3 - O CNPJ é inválido',__METHOD__);
		}
	}
	
	private function validarDigitosCNPJException(){
		try{
			$this->MitiValidacao->CNPJ('87210343000159');
		}catch(Exception $e){
			$this->afirmar($e->getMessage(),'#4 - O CNPJ é inválido',__METHOD__);
		}
	}
}
?>
