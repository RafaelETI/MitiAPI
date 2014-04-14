<?php
/*essa classe e necessaria apenas para o NetBeans nao xiar.
poderia ser passado apenas o diretorio para o phpunit*/

class MitiSuite extends PHPUnit_Framework_TestSuite{
	public static function suite(){
		$suite=new MitiSuite();
		
		foreach(self::toRun() as $file){
			$suite->addTestFile($file);
		}
		
		return $suite;
	}
	
	private static function toRun(){
		return self::rglob('*Test.php','/var/www/miti_modelo/unit/');
	}
	
	private static function rglob($pattern='*',$path='',$flags=0){
		$paths=glob($path.'*',GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT) or array();
		$files=glob($path.$pattern,$flags) or array();
		
		foreach($paths as $path){
			$files=array_merge($files,self::rglob($pattern,$path,$flags));
		}
		
		return $files;
	}
}