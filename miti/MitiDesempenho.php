<?php
/**
 * MitiAPI, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Medi��o de desempenho de procedimentos computacionais
 */
class MitiDesempenho{
	/**
	 * Mede o tempo de execu��o de um procedimento
	 * 
	 * Um uso comum � medir o tempo de uma requisi��o ao banco de dados.
	 * 
	 * @api
	 * @todo Talvez seja melhor retornar um float usando number_format().
	 * 
	 * @param float[] $microtimes Deve ser conseguido atrav�s da fun��o
	 * microtime(true), e deve ter dois valores, tempo inicial e tempo final,
	 * nos �ndices 0 e 1, respectivamente.
	 * 
	 * @param int $tamanho Tamanho do retorno.
	 * @return string Representa��o textual do tempo de execu��o do processo.
	 */
	public function medirTempoExecucao(array $microtimes,$tamanho=6){
		return substr($microtimes[1]-$microtimes[0],0,$tamanho);
	}
}
