function Login(){
	this.focar=function(){
		MitiElemento.getId('usuario').focus();
	};
}

var Login=new Login;
MitiPadrao.iniciar(function(){Login.focar();});
