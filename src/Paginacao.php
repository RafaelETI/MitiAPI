<?php
namespace Miti;

class Paginacao
{
    private $total;
    private $quantidade;
    private $pagina;
    private $qtdBotoes;
    private $inicio;
    private $qtdPaginas;
    private $botaoInicial;
    private $botaoFinal;

    public function __construct($total, $quantidade, $pagina, $qtdBotoes)
    {
        $this->total = $total;
        $this->quantidade = $quantidade;
        $this->pagina = $pagina;
        $this->qtdBotoes = $qtdBotoes;

        $this->inicio = ($this->pagina - 1) * $this->quantidade;
        $this->qtdPaginas = ceil($this->total / $this->quantidade) + 1;

        $metade = ceil($this->qtdBotoes / 2) - 1;
        $this->botaoInicial = $this->pagina - $metade;
        $this->botaoFinal = $this->pagina + $metade;
    }

    public function getInicio()
    {
        return $this->inicio;
    }

    public function criar($pagina, $on = '', $off = '', array $filtros = array())
    {
        $queryString = $this->formatarQueryString($filtros, $pagina);

        if ($this->pagina != 1) {
            $paginacao = "<a href='?$queryString=1'>&#8634;</a>";
        } else {
            $paginacao = "<span class='$off'>&#8634;</span>";
        }
        
        if ($this->pagina > 1) {
            $paginacao .= "<a href='?$queryString=".($this->pagina - 1)."'>&#8592;</a>";
        } else {
            $paginacao .= "<span class='$off'>&#8592;</span>";
        }
        
        for ($botao = $this->botaoInicial; $botao <= $this->botaoFinal; $botao++) {
            if ($this->pagina == $botao) {
                $paginacao .= "<span class='$on'>$botao</span>";
            } else {
                if ($botao < 1 || $botao >= $this->qtdPaginas) {
                    continue;
                }
                
                $paginacao .= "<a href='?$queryString=$botao'>$botao</a>";
            }
        }

        if (($this->pagina + 1) < $this->qtdPaginas) {
            $paginacao .= "<a href='?$queryString=".($this->pagina + 1)."'>&#8594;</a>";
        } else {
            $paginacao .= "<span class='$off'>&#8594;</span>";
        }

        if ($this->pagina != ($this->qtdPaginas - 1)) {
            $paginacao .= "<a href='?$queryString=".($this->qtdPaginas - 1)."'>&#8635;</a>";
        } else {
            $paginacao .= "<span class='$off'>&#8635;</span>";
        }
        
        return $paginacao;
    }

    private function formatarQueryString(array $filtros, $pagina)
    {
        $queryString = [];

        foreach ($filtros as $campo => $valor) {
            if ($campo === $pagina) {
                continue;
            }
            
            $queryString[] = "$campo=$valor";
        }

        $queryString = implode('&amp;', $queryString);
        
        if ($queryString) {
            $queryString .= '&amp;'.$pagina;
        } else {
            $queryString .= $pagina;
        }
        
        return $queryString;
    }
}
