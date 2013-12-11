//puro
inicio(function(){
	//mensagem de seguranca
	getId('modelo_del').onsubmit=function(){
		return confirm('Tem certeza que deseja deletar o registro?');
	};
});
