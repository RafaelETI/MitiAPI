<?php
class MitiSuite extends PHPUnit_Framework_TestSuite{
	private static function toRun(){
        return '/var/www/miti_modelo/unit/';
    }
}