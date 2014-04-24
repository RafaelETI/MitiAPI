MitiPadrao.iniciar(function(){new Cadastro;});

function Cadastro(){
	this.focar=function(){
		MitiElemento.getId('valor').focus();
	};
	
	this.contar=function(){
		MitiFormulario.contar('valor',15);
		MitiFormulario.contar('valor5',450);
	};
	
	this.focar();
	this.contar();
}
