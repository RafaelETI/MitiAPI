<?php
class I18nTest extends PHPUnit_Framework_TestCase{
	public function testTraduzirParaIngles(){
		$I18n = new \miti\I18n(CFG_RAIZ.'/unit/arquivos/miti.php', 'en');
		$this->assertSame("It's alive!", $I18n->traduzir('Está vivo!'));
	}
	
	public function testTraduzirParaFrances(){
		$I18n = new \miti\I18n(CFG_RAIZ.'/unit/arquivos/miti.php', 'fr');
		$this->assertSame('Est vivant!', $I18n->traduzir('Está vivo!'));
	}
}
