<?php
class MitiUnit{
	private $MitiBD;
	
	public function __construct(){
		$this->criarTabelas();
		$this->declararShutdownRemoverTabelas();
	}
	
	private function criarTabelas(){
		$this->MitiBD=new MitiBD();
		$this->criarMitiUnit();
		$this->criarMitiUnit2();
	}
	
	private function criarMitiUnit(){
		$sql='
			create table mitiunit(
				id tinyint(3) unsigned not null auto_increment,
				nome varchar(30) not null,
				idade tinyint(3) unsigned null,
				primary key(id)
			)
		';
		
		$this->MitiBD->requisitar($sql);
		$this->popularMitiUnit();
	}
	
	private function popularMitiUnit(){
		$this->MitiBD->requisitar('insert into mitiunit(id,nome)values(1,"Filme")');
	}
	
	private function criarMitiUnit2(){
		$sql='
			create table mitiunit2(
				id smallint(5) unsigned not null auto_increment,
				descricao varchar(1000) not null,
				categoria tinyint(3) unsigned not null,
				primary key(id),
				key categoria(categoria)
			)
		';
		
		$this->MitiBD->requisitar($sql);
		$this->popularMitiUnit2();
		$this->relacionarTabelas();
	}
	
	private function popularMitiUnit2(){
		$sql='
			insert into mitiunit2(id,descricao,categoria)values
			(90,"Gladiator (2000)",1),
			(91,"Spartacus (2004)",1),
			(92,"Ben Hur (1959)",1)
		';
	
		$this->MitiBD->requisitar($sql);
	}
	
	private function relacionarTabelas(){
		$sql='
			alter table mitiunit2
			add constraint mitiunit2_ibfk_1
			foreign key(categoria)references mitiunit(id)
			on update cascade
			on delete cascade
		';
		
		$this->MitiBD->requisitar($sql);
	}
	
	private function declararShutdownRemoverTabelas(){
		//nao pode ser usado o __destruct() por causa da heranca
		function removerTabelas($MitiBD){
			$MitiBD->requisitar('drop table mitiunit2');
			$MitiBD->requisitar('drop table mitiunit');
		}
		register_shutdown_function('removerTabelas',$this->MitiBD);
	}
	
	private function imprimir($title,$cor){
		$MitiTratamento=new MitiTratamento();
		$MitiTratamento->htmlSpecialChars($title);
		echo '<div title="'.$title.'" style="height:20px; width:20px; border:solid 1px; float:left; cursor:help; background:'.$cor.';"></div>';
	}
	
	public function afirmar($valores,$afirmacao,$title){
		$cor='green';
		
		if(is_array($valores)){
			$this->afirmarArray($valores,$afirmacao,$title,$cor);
		}else{
			$this->afirmarScalar($valores,$afirmacao,$title,$cor);
		}
		
		$this->imprimir($title,$cor);
	}
	
	private function afirmarArray($valores,$afirmacao,&$title,&$cor){
		foreach($valores as $i=>$v){
			if($v!==$afirmacao[$i]){
				$cor='red';
				
				$title.="\n\n";
				$title.='Valor: ('.gettype($v).')'."\n".$v;
				$title.="\n\n";
				$title.='Afirmação: ('.gettype($afirmacao[$i]).')'."\n".$afirmacao[$i];
				
				break;
			}
		}
	}
	
	private function afirmarScalar($valores,$afirmacao,&$title,&$cor){
		if($valores!==$afirmacao){
			$cor='red';
			
			$title.="\n\n";
			$title.='Valor ('.gettype($valores).'):'."\n".$valores;
			$title.="\n\n";
			$title.='Afirmação ('.gettype($afirmacao).'):'."\n".$afirmacao;
		}
	}
}
?>
