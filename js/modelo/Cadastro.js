function Cadastro(){
	this.focar=function(){
		MitiElemento.getId('valor').focus();
	};
	
	this.contar=function(){
		MitiFormulario.contar('valor',15);
		MitiFormulario.contar('valor5',450);
	};
}

var Cadastro=new Cadastro;

MitiPadrao.iniciar(
	function(){
		Cadastro.focar();
		Cadastro.contar();
	}
);
