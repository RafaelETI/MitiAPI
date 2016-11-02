<?php
use PHPUnit\Framework\TestCase;

class I18nTest extends TestCase{
	public function testTraduzirParaIngles(){
		$I18n = new \Miti\I18n('../tests/arquivos/miti.php', 'en');
		$this->assertSame("It's alive!", $I18n->traduzir('Está vivo!'));
	}
	
	public function testTraduzirParaFrances(){
		$I18n = new \Miti\I18n('../tests/arquivos/miti.php', 'fr');
		$this->assertSame('Est vivant!', $I18n->traduzir('Está vivo!'));
	}
}
