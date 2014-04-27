function Busca(){
	this.focar=function(){
		MitiElemento.getId('valor').focus();
	};
	
	this.contar=function(){
		MitiFormulario.contar('valor',15);
	};
}

var Busca=new Busca;

MitiPadrao.iniciar(
	function(){
		Busca.focar();
		Busca.contar();
	}
);
