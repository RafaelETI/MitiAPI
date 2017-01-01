<?php
namespace Miti;

class Banco
{
    private $conexao;
    private $requisicao;
    private $afetados;
    private $id;

    public function __construct(array $config)
    {
        $this->verificarExtensao();
        $this->conexao = @new \mysqli($config['banco']['servidor'], $config['banco']['usuario'], $config['banco']['senha'], $config['banco']['nome']);
        $this->verificarConexao();
        $this->definirCharset($config['banco']['charset']);
        $this->conexao->autocommit(false);
    }

    private function verificarExtensao()
    {
        if (!extension_loaded('mysqli')) {
            throw new \RuntimeException('A classe '.__CLASS__.' depende da extensão mysqli');
        }
    }

    private function verificarConexao()
    {
        if ($this->conexao->connect_error) {
            if (ini_get('display_errors')) {
                $mensagem = $this->conexao->connect_error;
            } else {
                $mensagem = 'Não foi possível conectar ao banco de dados';
            }
            
            throw new \RuntimeException($mensagem);
        }
    }

    private function definirCharset($charset)
    {
        if (!$this->conexao->set_charset($charset)) {
            throw new \DomainException('Houve um erro ao definir o charset');
        }
    }

    public function escapar($valores)
    {
        if (is_array($valores)) {
            return $this->escaparArray($valores);
        } else {
            return $this->escaparString($valores);
        }
    }

    private function escaparArray(array $valores)
    {
        foreach ($valores as &$valor) {
            $valor = $this->escaparString($valor);
        }
        
        return $valores;
    }

    private function escaparString($valor)
    {
        return $this->conexao->real_escape_string($valor);
    }

    public function requisitar($sql)
    {
        $this->requisicao = $this->conexao->query($sql);

        $this->verificarErroDeRequisicao($sql);
        $this->setAfetados();
        $this->setId();

        return $this;
    }

    private function verificarErroDeRequisicao($sql)
    {
        if ($this->conexao->error) {
            if (ini_get('display_errors')) {
                $mensagem = "#{$this->conexao->errno} {$this->conexao->error} - $sql";
            } else {
                switch ($this->conexao->errno) {
                    case 1062: $mensagem = 'O registro já existe.'; break;
                    default: $mensagem = "#{$this->conexao->errno} Houve um erro ao realizar a requisição."; break;
                }
            }

            throw new \UnexpectedValueException($mensagem);
        }

        return $this;
    }

    private function setAfetados()
    {
        $this->afetados = $this->conexao->affected_rows;
    }

    public function getAfetados()
    {
        return $this->afetados;
    }

    private function setId()
    {
        $this->id = $this->conexao->insert_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function cometer()
    {
        $this->conexao->commit();
    }

    public function rebobinar()
    {
        $this->conexao->rollback();
    }

    public function vetorizar()
    {
        return $this->requisicao->fetch_assoc();
    }

    public function quantificar()
    {
        return $this->requisicao->num_rows;
    }

    public function mapear()
    {
        return $this->requisicao->fetch_fields();
    }

    public function __destruct()
    {
        $this->conexao->close();
    }
}
