<?php
class ModeloTest extends PHPUnit_Framework_TestCase{
//	private static $MitiBD;
//	private static $id=array();
//	private static $campo;
//	private static $imagem;
//	private static $tipo;
//	private $Modelo;
//	
//	public static function setUpBeforeClass(){
//		unset($_SESSION['login']);
//		self::$MitiBD=MitiBD::getInstance();
//		
//		$_FILES['imagem']['name'][0]='modelo.jpg';
//		$_FILES['imagem']['tmp_name'][0]=RAIZ.'img/unit/modelo.jpg';
//		$_FILES['imagem']['size'][0]=44851;
//		$_FILES['imagem']['type'][0]='image/jpeg';
//		
//		self::$campo='Campo';
//		self::$imagem=file_get_contents($_FILES['imagem']['tmp_name'][0]);
//		self::$tipo='image/jpeg';
//	}
//	
//	protected function setUp(){
//		unset($_SESSION['login']);
//		$this->Modelo=new Modelo;
//	}
//	
//	public function testCadastrar(){
//		$_SESSION['login']=true;
//		
//		$_POST['campo']=self::$campo;
//		self::$id[0]=$this->Modelo->cadastrar();
//		
//		$_POST['campo']='Campo 2';
//		self::$id[1]=$this->Modelo->cadastrar();
//	}
//	
//	public function testEditar(){
//		$_SESSION['login']=true;
//		
//		$_POST['id']=self::$id[0];
//		$_POST['campo']='Campo 3';
//		$this->Modelo->editar();
//	}
//	
	public function testObter(){
//		$this->assertSame(
//			array(
//				'id'=>(string)self::$id[0],
//				'campo'=>'Campo 3',
//				'imagem'=>self::$imagem,
//				'tipo'=>self::$tipo,
//			),
//		
//			$this->Modelo->obter(self::$id[0])
//		);
	}
//	
//	public function testObterTodos(){
//		$primeiro=false;
//		$resultado=false;
//		$this->Modelo->obterTodos();
//		
//		while($modelo=self::$MitiBD->obterAssoc()){
//			if($modelo['id']==self::$id[1]){
//				$primeiro=true;
//			}
//			
//			if($primeiro&&$modelo['id']==self::$id[0]){
//				$resultado=true;
//			}
//		}
//		
//		$this->assertTrue($resultado);
//	}
//	
//	public static function tearDownAfterClass(){
//		$_SESSION['login']=true;
//		$Modelo=new Modelo;
//		
//		foreach(self::$id as $v){
//			$_GET['id']=$v;
//			$Modelo->excluir();
//		}
//		
//		self::$MitiBD->cometer();
//	}
}
