$(document).ready(function(){
	//efeito inicial
	$('#geral').fadeIn(1000);
	
	//efeito no menu
	$('.menu').click(function(){
		$('div[id$="oculto"]').css('display','none');
		$('#'+this.id+'_oculto').fadeIn(1000);
	});
});
