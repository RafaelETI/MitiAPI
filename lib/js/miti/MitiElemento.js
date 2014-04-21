function MitiElemento(){
	this.getId=function(id){
		return document.getElementById(id);
	};
	
	this.getTag=function(tag){
		return document.getElementsByTagName(tag);
	};
	
	this.getClass=function(classe){
		//workaround para o ie 8
		if(!document.getElementsByClassName){
			document.getElementsByClassName=function(className){
				return this.querySelectorAll('.'+className);
			};
			
			Element.prototype.getElementsByClassName=document.getElementsByClassName;
		}

		return document.getElementsByClassName(classe);
	};
}