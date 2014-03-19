<?php
class MitiValidacao{
	public function tamanho($valor,$tamanho){
		if(!$valor){
			return;
		}
		
		if(strlen($valor)!=$tamanho){
			throw new Exception('O valor deve conter até '.$tamanho.' caractéres');
		}
	}
	
	public function email($valor){
		if(!$valor){
			return;
		}
		
		if(!preg_match('/^\w{2,}@\w{2,}\.(\w|\.){2,}$/',$valor)){
			throw new Exception('O e-mail é inválido');
		}
	}
	
	public function vazio($valores){
		if(is_array($valores)){
			$this->vazioArray($valores);
		}else{
			$this->vazioScalar($valores);
		}
	}
	
	private function vazioArray($valores){
		foreach($valores as $v){
			if(!$v){
				throw new Exception('Valor vazio');
			}
		}
	}
	
	private function vazioScalar($valores){
		if(!$valores){
			throw new Exception('Valor vazio');
		}
	}
	
	public function upload($file,$peso,array $tipos){
		//a tag form deve conter "enctype='multipart/form-data'", e o "name" deve conter "[]"
		if(isset($_FILES[$file]['name'])){
			foreach($_FILES[$file]['name'] as $i=>$v){
				$this->validarPeso($file,$i,$peso);
				$this->validarTipos($file,$i,$tipos);
			}
		}else{
			return;
		}
	}
	
	private function validarPeso($file,$i,$peso){
		if($_FILES[$file]['size'][$i]>$peso){
			throw new Exception('O arquivo excede o tamanho permitido');
		}
	}
	
	private function validarTipos($file,$i,array $tipos){
		$ok=false;
		
		foreach($tipos as $v){
			if(strpos($_FILES[$file]['type'][$i],$v)!==false){
				$ok=true;
			}
		}
		
		if(!$ok){
			throw new Exception('O tipo do arquivo é inválido');
		}
	}
	
	public function uploadImagem($file,$largura,$altura){
		//a tag form deve conter "enctype='multipart/form-data'", e o "name" deve conter "[]"
		if(isset($_FILES[$file]['name'])){
			foreach($_FILES[$file]['name'] as $i=>$v){
				$tamanho=getimagesize($_FILES[$file]['tmp_name'][$i]);
				$this->validarTamanho($tamanho,$largura,$altura);
				$this->validarProporcoes($tamanho,$largura,$altura);
			}
		}else{
			return;
		}
	}
	
	private function validarTamanho($tamanho,$largura,$altura){
		if($tamanho[0]<$largura){
			throw new Exception('A largura da imagem é menor do que o mínimo permitido');
		}
		
		if($tamanho[1]<$altura){
			throw new Exception('A altura da imagem é menor do que o mínimo permitido');
		}
	}
	
	private function validarProporcoes($tamanho,$largura,$altura){
		$prop_args=$largura/$altura;
		$prop_min=$prop_args-0.1;
		$prop_max=$prop_args+0.1;
		$prop_img=$tamanho[0]/$tamanho[1];
		
		if($prop_img<$prop_min){
			throw new Exception('A proporção da imagem é inválida, excedendo verticalmente');
		}
		
		if($prop_img>$prop_max){
			throw new Exception('A proporção da imagem é inválida, excedendo horizontalmente');
		}
	}
	
	public function CPF($cpf){
		if(!$cpf){
			return;
		}
		
		$this->validarQuantidadeCaracteres($cpf);
		$this->validarApenasNumeros($cpf);
		$this->validarSequenciaIgual($cpf);
		$this->validarDigitosCPF($cpf);
	}
	
	private function validarQuantidadeCaracteres($cpf){
		if(strlen($cpf)!==11){
			throw new Exception('#1 - O CPF é inválido');
		}
	}
	
	private function validarApenasNumeros($cpf){
		if(!preg_match('/\d{11}/',$cpf)){
			throw new Exception('#2 - O CPF é inválido');
		}
	}
	
	private function validarSequenciaIgual($cpf){
		for($i=1,$y=$cpf[0];$i<=10;$i++){
			if($y!=$cpf[$i]){
				break;
			}
			
			if($i==10){
				throw new Exception('#3 - O CPF é inválido');
			}
		}
	}
	
	private function validarDigitosCPF($cpf){
		for($t=9;$t<11;$t++){
			for($d=0,$c=0;$c<$t;$c++){
				$d+=$cpf[$c]*(($t+1)-$c);
			}
			
			$d=((10*$d)%11)%10;
			
			if($cpf[$c]!=$d){
				throw new Exception('#4 - O CPF é inválido');
			}
		}
	}
	
	public function CNPJ($cnpj){
		if(!$cnpj){
			return;
		}
		
		$this->validarQuantidadeCaracteresCNPJ($cnpj);
		$this->validarApenasNumerosCNPJ($cnpj);
		$this->validarSequenciaZeros($cnpj);
		$this->validarDigitosCNPJ($cnpj);
	}
	
	private function validarQuantidadeCaracteresCNPJ($cnpj){
		if(strlen($cnpj)!==14){
			throw new Exception('#1 - O CNPJ é inválido');
		}
	}
	
	private function validarApenasNumerosCNPJ($cnpj){
		if(!preg_match('/\d{14}/',$cnpj)){
			throw new Exception('#2 - O CNPJ é inválido');
		}
	}
	
	private function validarSequenciaZeros($cnpj){
		if($cnpj=='00000000000000'){
			throw new Exception('#3 - O CNPJ é inválido');
		}
	}
	
	private function validarDigitosCNPJ($cnpj){
		$p=array(
			array('x'=>5,'i'=>array(11,4),'p'=>12),
			array('x'=>6,'i'=>array(12,5),'p'=>13)
		);
		
		for($y=0;$y<=1;$y++){
			for($i=0,$x=$p[$y]['x'],$soma=0;$i<=$p[$y]['i'][0];$i++){
				if($i===$p[$y]['i'][1]){
					$x=9;
				}
				
				$soma+=$cnpj[$i]*$x--;
			}
			
			$resto=$soma%11;
			
			if($resto<2){
				$digito=0;
			}else{
				$digito=11-$resto;
			}
			
			if($cnpj[$p[$y]['p']]!=$digito){
				throw new Exception('#4 - O CNPJ é inválido');
			}
		}
	}
}