MitiPadrao.iniciar(function(){new Busca;});

function Busca(){
	this.focar=function(){
		MitiElemento.getId('valor').focus();
	};
	
	this.contar=function(){
		MitiFormulario.contar('valor',15);
	};
	
	this.focar();
	this.contar();
}