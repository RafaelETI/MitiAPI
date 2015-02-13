<?php
/**
 * Miti API, 2014 - 2015
 * 
 * @author Rafael Barros <admin@rafaelbarros.eti.br>
 * @link https://github.com/RafaelETI/MitiAPI
 */
namespace miti;

/**
 * Internacionalização da informação
 * 
 * Não vi motivo para usar a biblioteca gettext com arquivos .po.
 */
class I18n{
	/**
	 * @var array[] Estrutura de dados com as traduções estáticas do sistema.
	 */
	private $mensagens;
	
	/**
	 * @var string Um identificador de dois caractéres.
	 */
	private $idioma;
	
	/**
	 * Requere o arquivo com os dados e suas traduções e define o idioma
	 * 
	 * O arquivo deve conter o retorno de um array no seguinte formato:
	 * array('Está vivo!' => array('en' => "It's alive!", 'fr' => 'Est vivant!'))
	 * 
	 * @api
	 */
	public function __construct($caminho, $idioma){
		$this->mensagens = require $caminho;
		$this->idioma = $idioma;
	}
	
	/**
	 * Traduz o texto
	 * 
	 * @api
	 * @param string $texto
	 */
	public function traduzir($texto){
		return isset($this->mensagens[$texto][$this->idioma])? $this->mensagens[$texto][$this->idioma]: $texto;
	}
}
