function MitiPadrao(){
	this.iniciar=function(funcao){
		var intervalo=setInterval(
			function(){
				if(document.body!==null){
					funcao();
					return clearInterval(intervalo);
				}
			},
			
			1
		);
	};
}