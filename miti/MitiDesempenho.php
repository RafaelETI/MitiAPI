<?php
/**
 * MitiAPI, 2014
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */

/**
 * Medição de desempenho de procedimentos computacionais
 */
class MitiDesempenho{
	/**
	 * Mede o tempo de execução de um procedimento
	 * 
	 * Um uso comum é medir o tempo de uma requisição ao banco de dados.
	 * 
	 * @api
	 * @todo Talvez seja melhor retornar um float usando number_format().
	 * 
	 * @param float[] $microtimes Deve ser conseguido através da função
	 * microtime(true), e deve ter dois valores, tempo inicial e tempo final,
	 * nos índices 0 e 1, respectivamente.
	 * 
	 * @param int $tamanho Tamanho do retorno.
	 * @return string Representação textual do tempo de execução do processo.
	 */
	public function medirTempoExecucao(array $microtimes,$tamanho=6){
		return substr($microtimes[1]-$microtimes[0],0,$tamanho);
	}
}
