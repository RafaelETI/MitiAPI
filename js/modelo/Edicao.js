function Edicao(){
	this.focar=function(){
		MitiElemento.getId('valor').focus();
	};
	
	this.contar=function(){
		MitiFormulario.contar('valor',15);
		MitiFormulario.contar('valor5',450);
	};
}

var Edicao=new Edicao;

MitiPadrao.iniciar(
	function(){
		Edicao.focar();
		Edicao.contar();
	}
);
