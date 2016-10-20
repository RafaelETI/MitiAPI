<?php
/**
 * Miti Lib, 2014 - 2016
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace Miti;

/**
 * Consumo de serviço REST
 */
class RIP{
	/**
	 * @var mixed[] Configurações de sistema. É esperado um índice 'rest' contendo
	 * um array com os índices 'servidor', 'usuario' e 'senha'.
	 */
	private $config;
	
	/**
	 * @var resource
	 */
	private $curl;
	
	/**
	 * @var string Identificador de uma sessão de transação
	 */
	private $id;
	
	/**
	 * Estabelece uma conexão HTTP
	 * 
	 * @param mixed[] $config
	 * 
	 * @throws \Exception
	 */
	public function __construct(array $config){
		$this->verificarExtensao();
		
		$this->config = $config;
		$this->curl = curl_init($config['rest']['servidor']);
	}
	
	public function getId(){return $this->id;}
	
	/**
	 * Verifica carregamento de extensão para trabalhar com requisição HTTP
	 * 
	 * @throws \Exception
	 */
	private function verificarExtensao(){
		if(!extension_loaded('curl')){
			throw new \Exception('A classe '.__CLASS__.' depende da extensão curl.');
		}
	}
	
	/**
	 * Faz uma requisição ao servidor
	 * 
	 * @param string $metodo
	 * @param mixed[] $parametros
	 * 
	 * @return Object
	 * 
	 * @throws \Exception
	 */
	public function requisitar($metodo = null, array $parametros = []){
		$this->parametrizar($metodo, $parametros);
		
		$requisicao = json_decode(explode("\r\n\r\n", curl_exec($this->curl))[1]);
		
		if(isset($requisicao->number)){throw new \Exception($requisicao->description);}
		if(!$requisicao){throw new \Exception('Falha na requisição.');}
		
		return $requisicao;
	}
	
	/**
	 * Define parâmetros da requisição
	 * 
	 * @param string $metodo
	 * @param mixed[] $parametros
	 */
	private function parametrizar($metodo, $parametros){
		curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($this->curl, CURLOPT_HEADER, true);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

		$json = json_encode($parametros);
		$post = ['method' => $metodo, 'input_type' => 'json', 'response_type' => 'json', 'rest_data' => $json];
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);
	}

	/**
	 * Fecha a conexão com o servidor
	 */
	public function __destruct(){
		curl_close($this->curl);
	}
}
