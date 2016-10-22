<?php
namespace Miti;

class Banco{
	private $Conexao;
	private $Requisicao;
	private $afetados;
	private $id;
	
	public function __construct(array $config){
		$this->verificarExistenciaDaExtensao();
		$this->Conexao = @new \mysqli($config['banco']['servidor'], $config['banco']['usuario'], $config['banco']['senha'], $config['banco']['nome']);
		$this->verificarErroDeConexao();
		$this->definirCharset($config['banco']['charset']);
		$this->Conexao->autocommit(false);
	}
	
	private function verificarExistenciaDaExtensao(){
		if(!extension_loaded('mysqli')){throw new \RuntimeException('A classe '.__CLASS__.' depende da extensão mysqli.');}
	}
	
	private function verificarErroDeConexao(){
		if($this->Conexao->connect_error){
			$mensagem = ini_get('display_errors')? $this->Conexao->connect_error: 'Não foi possível conectar ao banco de dados.';
			throw new \RuntimeException($mensagem);
		}
	}
	
	private function definirCharset($charset){
		if(!$this->Conexao->set_charset($charset)){throw new \DomainException('Houve um erro ao definir o charset.');}
	}
	
	public function escapar($valores){
		return is_array($valores)? $this->escaparArray($valores): $this->escaparString($valores);
	}
	
	private function escaparArray(array $valores){
		foreach($valores as &$valor){$valor = $this->escaparString($valor);}
		return $valores;
	}
	
	private function escaparString($valor){return $this->Conexao->real_escape_string($valor);}
	
	public function requisitar($sql){
		$this->Requisicao = $this->Conexao->query($sql);
		
		$this->verificarErroDeRequisicao($sql);
		$this->setAfetados();
		$this->setId();
		
		return $this;
	}
	
	private function verificarErroDeRequisicao($sql){
		if($this->Conexao->error){
			if(ini_get('display_errors')){
				$mensagem = "#{$this->Conexao->errno} {$this->Conexao->error} - $sql";
			}else{
				switch($this->Conexao->errno){
					case 1062: $mensagem = 'O registro já existe.'; break;
					default: $mensagem = "#{$this->Conexao->errno} Houve um erro ao realizar a requisição."; break;
				}
			}
			
			throw new \UnexpectedValueException($mensagem);
		}
		
		return $this;
	}
	
	private function setAfetados(){
		$this->afetados = $this->Conexao->affected_rows;
	}
	
	public function getAfetados(){
		return $this->afetados;
	}
	
	private function setId(){
		$this->id = $this->Conexao->insert_id;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function cometer(){
		$this->Conexao->commit();
	}
	
	public function rebobinar(){
		$this->Conexao->rollback();
	}
	
	public function vetorizar(){
		return $this->Requisicao->fetch_assoc();
	}
	
	public function quantificar(){
		return $this->Requisicao->num_rows;
	}
	
	public function mapear(){
		return $this->Requisicao->fetch_fields();
	}
	
	public function __destruct(){
		$this->Conexao->close();
	}
}
