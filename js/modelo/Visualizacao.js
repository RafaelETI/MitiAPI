function Visualizacao(){
	this.confirmarSubmit=function(){
		MitiFormulario.confirmarSubmit();
	};
}

var Visualizacao=new Visualizacao;
MitiPadrao.iniciar(function(){Visualizacao.confirmarSubmit();});
