//==========basicas==========

function getId(id){
	return document.getElementById(id);
}

function getTag(tag){
	return document.getElementsByTagName(tag);
}

function getClass(classe){
	//workaround para o ie 8
	if(!document.getElementsByClassName){
		document.getElementsByClassName=function(className){return this.querySelectorAll('.'+className);};
		Element.prototype.getElementsByClassName=document.getElementsByClassName;
	}
	
	return document.getElementsByClassName(classe);
}

function mitiIniciar(funcao){
	var clock=setInterval(function(){
		if(document.body!=null){
			funcao();
			return clearInterval(clock);
		}
	},1);
}

//==========auxiliares==========

function mitiContar(id,quantidade){
	var elemento=getId(id);

	elemento.onkeyup=function(){
		var valor=elemento.value;
	
		var resto=quantidade-valor.length;
	
		var contagem=getId(id+'_miticontar');
		contagem.innerHTML=resto;
	
		if(contagem.style.color!='#007e7a'&&resto>=0){contagem.style.color='#007e7a';}
		else if(contagem.style.color!='red'&&resto<0){contagem.style.color='red';}
	}
	
	elemento.onblur=function(){
		var contagem=getId(id+'_miticontar');
		contagem.innerHTML='';
	}
}

//==========pagina==========

mitiIniciar(function(){
	var submits=getClass('mitisubmit');
	for(var x=0;x<submits.length;x++){
		submits[x].onsubmit=function(){
			return confirm('Tem certeza?');
		};
	}
	
	var clicks=getClass('miticlick');
	for(var x=0;x<clicks.length;x++){
		clicks[x].onclick=function(){
			return confirm('Tem certeza?');
		};
	}
});

mitiIniciar(function(){
	var mitivalidacao=getClass('mitivalidacao')[0];

	if(mitivalidacao){
		mitivalidacao.onsubmit=function(){
			var vazios=getClass('mitivazio');
			
			for(var i=0;i<vazios.length;i++){
				if(!vazios[i].value){
					alert('Valor vazio');
					vazios[i].style.borderColor='red';
					vazios[i].focus();
					return false;
				}else{
					vazios[i].style.borderColor='#b6b6b6';
				}
			}
		};
	}
});

$(document).ready(function(){
	$('#geral').fadeIn(1000);
	
	$('.menu').click(function(){
		$('div[id$="oculto"]').css('display','none');
		$('#'+this.id+'_oculto').fadeIn(1000);
	});
});
