//==========básicas==========

function getId(id){
	return document.getElementById(id);
}

function getClass(classe){
	//workaround para o ie 8
	if(!document.getElementsByClassName){
		document.getElementsByClassName=function(className){return this.querySelectorAll('.'+className);};
		Element.prototype.getElementsByClassName=document.getElementsByClassName;
	}

	return document.getElementsByClassName(classe);
}

function getTag(tag){
	return document.getElementsByTagName(tag);
}

function inicio(funcao){
	var clock=setInterval(function(){
		if(document.body!=null){
			funcao();
			return clearInterval(clock);
		}
	},1);
}

//==========auxiliares==========

function auxFormCont(id,quantidade){
	var elemento=getId(id);

	elemento.onkeyup=function(){
		var valor=elemento.value;
	
		var resto=quantidade-valor.length;
	
		var contagem=getId(id+'_auxformcont');
		contagem.innerHTML=resto;
	
		if(contagem.style.color!='#007e7a'&&resto>=0){contagem.style.color='#007e7a';}
		else if(contagem.style.color!='red'&&resto<0){contagem.style.color='red';}
	}
	
	elemento.onblur=function(){
		var contagem=getId(id+'_auxformcont');
		contagem.innerHTML='';
	}
}

//==========pagina==========

//puro
inicio(function(){
	//mensagem de exclusao
	for(var x=0;x<getClass('del').length;x++){
		getClass('del')[x].onsubmit=function(){
			return confirm('Tem certeza que deseja deletar?');
		};
	}
});

//jquery
$(document).ready(function(){
	//efeito inicial
	$('#geral').fadeIn(1000);
	
	//efeito no menu
	$('.menu').click(function(){
		$('div[id$="oculto"]').css('display','none');
		$('#'+this.id+'_oculto').fadeIn(1000);
	});
});

