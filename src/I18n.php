<?php
namespace Miti;

class I18n{
	private $mensagens;
	private $idioma;
	
	public function __construct($caminho, $idioma){
		$this->mensagens = require $caminho;
		$this->idioma = $idioma;
	}
	
	public function traduzir($texto){
		return isset($this->mensagens[$texto][$this->idioma])? $this->mensagens[$texto][$this->idioma]: $texto;
	}
}
