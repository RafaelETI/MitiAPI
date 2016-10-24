<?php
class RIPTest extends PHPUnit_Framework_TestCase{
    private static $config;

    public static function setUpBeforeClass(){
        global $config;
        self::$config = $config;
    }

    public function testGetRequisitarJson(){
        $rip = new \Miti\RIP(self::$config);
        $rip->setHttp(CURL_HTTP_VERSION_1_1);
        $rip->setGet();
        $rip->setHeader('');
        $rip->setUrl('?json={0:true}');
        $this->assertSame(true, $rip->requisitarJson()->validate);
    }

    public function testPostRequisitarJson(){
        $rip = new \Miti\RIP(self::$config);
        $rip->setPost();
        $rip->setUrl('?json={0:true}');
        $rip->setPostFields([]);
        $this->assertSame(true, $rip->requisitarJson()->validate);
    }
}
