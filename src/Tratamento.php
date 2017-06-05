<?php
namespace Miti;

class Tratamento
{
    public static function requerer($caminho)
    {
        $caminhoSemBarra = mb_substr($caminho, 1);
        $hash = md5(file_get_contents($caminhoSemBarra));

        $partes = explode('.', $caminho);
        $extensao = end($partes);

        if ($extensao === 'js') {
            $html = "<script src='$caminho?hash=$hash'></script>\n";
        } elseif ($extensao === 'css') {
            $html = "<link rel='stylesheet' href='$caminho?hash=$hash' />\n";
        }
        
        return $html;
    }

    public static function indexar($vetor, array $indices, $valor = '')
    {
        foreach ($indices as $indice) {
            if (!isset($vetor[$indice])) {
                $vetor[$indice] = $valor;
            }
        }

        return $vetor;
    }

    public static function escapar($valores, $charset = 'UTF-8')
    {
        if (!$valores) {
            return;
        }
        
        if (is_array($valores)) {
            $valores = self::escaparArray($valores,$charset);
        } else {
            $valores = self::escaparScalar($valores,$charset);
        }
        
        return $valores;
    }

    private static function escaparArray(array $valores, $charset)
    {
        foreach ($valores as &$valor) {
            $valor = self::escaparScalar($valor, $charset);
        }
        
        return $valores;
    }

    private static function escaparScalar($valor, $charset)
    {
        return htmlspecialchars($valor, ENT_QUOTES, $charset);
    }

    public static function encurtar($valores, $tamanho)
    {
        if (!$valores) {
            return;
        }
        
        if (is_array($valores)) {
            $valores = self::encurtarArray($valores,$tamanho);
        } else {
            $valores = self::encurtarScalar($valores,$tamanho);
        }
        
        return $valores;
    }

    private static function encurtarArray(array $valores, $tamanho)
    {
        foreach ($valores as &$valor) {
            $valor = self::encurtarScalar($valor, $tamanho);
        }
        
        return $valores;
    }

    private static function encurtarScalar($valor, $tamanho)
    {
        if (strlen($valor) > $tamanho + 2) {
            $valor = mb_substr($valor, 0, $tamanho).'...';
        }
        
        return $valor;
    }
}
