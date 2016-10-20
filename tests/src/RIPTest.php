<?php
class RIPTest extends PHPUnit_Framework_TestCase{
	private static $config = ['rest' => ['servidor' => 'http://ip.jsontest.com/']];
	private static $rip;
	
	public static function setUpBeforeClass(){
		self::$rip = new \Miti\RIP(self::$config);
	}
	
	public function testRequisitar(){
		$this->assertSame('177.182.179.194', self::$rip->requisitar()->ip);
	}
}
