<?php
namespace Miti;

class RIP{
	private $config;
	private $curl;
	private $id;

	public function __construct(array $config){
		$this->verificarExtensao();
		
		$this->config = $config;
		$this->curl = curl_init($config['rest']['servidor']);
	}
	
	public function getId(){return $this->id;}
	
	private function verificarExtensao(){
		if(!extension_loaded('curl')){
			throw new \Exception('A classe '.__CLASS__.' depende da extensão curl.');
		}
	}
	
	public function requisitar($metodo = null, array $parametros = []){
		$this->parametrizar($metodo, $parametros);
		
		$requisicao = json_decode(explode("\r\n\r\n", curl_exec($this->curl))[1]);
		
		if(isset($requisicao->number)){throw new \Exception($requisicao->description);}
		if(!$requisicao){throw new \Exception('Falha na requisição.');}
		
		return $requisicao;
	}
	
	private function parametrizar($metodo, $parametros){
		curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($this->curl, CURLOPT_HEADER, true);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

		$json = json_encode($parametros);
		$post = ['method' => $metodo, 'input_type' => 'json', 'response_type' => 'json', 'rest_data' => $json];
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);
	}

	public function __destruct(){
		curl_close($this->curl);
	}
}
