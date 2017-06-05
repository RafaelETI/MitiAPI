<?php
namespace Miti;

class RIP
{
    private $config;
    private $curl;
    private $header = [];
    private $id;

    public function __construct(array $config)
    {
        $this->verificarExtensao();
        $this->config = $config;
        $this->curl = curl_init($this->config['rest']['servidor']);

        $this->setHttp();
        curl_setopt($this->curl, CURLOPT_HEADER, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    private function verificarExtensao()
    {
        if (!extension_loaded('curl')) {
            throw new \Exception('A classe '.__CLASS__.' depende da extensão curl');
        }
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setHttp($http = CURL_HTTP_VERSION_1_0)
    {
        curl_setopt($this->curl, CURLOPT_HTTP_VERSION, $http);
        return $this;
    }

    public function setGet()
    {
        curl_setopt($this->curl, CURLOPT_HTTPGET, true);
        return $this;
    }

    public function setPost()
    {
        curl_setopt($this->curl, CURLOPT_POST, true);
        return $this;
    }

    public function setPut()
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->setHeader('Content-Type: application/json');
        return $this;
    }

    public function setHeader($valor)
    {
        $this->header[] = $valor;
        return $this;
    }

    public function setUrl($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->config['rest']['servidor'].$url);
        return $this;
    }

    public function setPostFields($post)
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post);
        return $this;
    }
    
    public function setUserPwd($userPwd)
    {
        curl_setopt($this->curl, CURLOPT_USERPWD, $userPwd);
        return $this;
    }

    public function requisitar()
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);
        
        if (!$requisicao = curl_exec($this->curl)) {
            throw new \Exception('Falha na requisição');
        }
        
        return $requisicao;
    }

    public function requisitarJson()
    {
        return json_decode(explode("\r\n\r\n", $this->requisitar())[1]);
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }
}
