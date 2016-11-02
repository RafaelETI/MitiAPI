<?php
use PHPUnit\Framework\TestCase;

class RIPTest extends TestCase{
    private static $config;
    private $rip;

    public static function setUpBeforeClass(){
        global $config;
        self::$config = $config;
    }

    protected function setUp(){
        $this->rip = new \Miti\RIP(self::$config);
    }
    
    protected function tearDown(){
        unset($this->rip);
    }
    
    public function testSetGetId(){
        $this->rip->setId(1);
        $this->assertSame(1, $this->rip->getId());
    }
    
    public function testGetRequisitarJson(){
        $this->rip->setHttp(CURL_HTTP_VERSION_1_1);
        $this->rip->setGet();
        $this->rip->setHeader('');
        $this->rip->setUrl('?json={0:true}');
        $requisicaoJson = $this->rip->requisitarJson();
        $this->assertSame(true, $requisicaoJson->validate);
    }

    public function testPostRequisitarJson(){
        $this->rip->setPost();
        $this->rip->setUrl('?json={0:true}');
        $this->rip->setPostFields([]);
        $requisicaoJson = $this->rip->requisitarJson();
        $this->assertSame(true, $requisicaoJson->validate);
    }
}
