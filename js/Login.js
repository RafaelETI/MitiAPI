MitiPadrao.iniciar(function(){new Login;});

function Login(){
	this.focar=function(){
		MitiElemento.getId('usuario').focus();
	};
	
	this.focar();
}
