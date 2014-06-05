var MitiPadrao=new MitiPadrao;
var MitiElemento=new MitiElemento;
var MitiFormulario=new MitiFormulario('#007E7A');

function Geral(){
	this.enfeitar=function(){
		$('#geral').fadeIn(1000);
		
		$('.menu').click(function(){
			$('div[id$="oculto"]').css('display','none');
			$('#'+this.id+'_oculto').fadeIn(1000);
		});
	};
}

var Geral=new Geral;
MitiPadrao.iniciar(function(){Geral.enfeitar();});
