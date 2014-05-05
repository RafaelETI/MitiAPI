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
	
	this.getQueryString=function(indice){
		var result={};
		var keyValuePairs=location.search.slice(1).split('&');

		//ie8 workaround
		if(!Array.prototype.forEach){
			Array.prototype.forEach=function(fn, scope){
				for(var i=0,len=this.length;i<len;++i){
					fn.call(scope,this[i],i,this);
				}
			};
		}

		keyValuePairs.forEach(
			function(keyValuePair){
				keyValuePair=keyValuePair.split('=');
				result[keyValuePair[0]]=keyValuePair[1]||'';
			}
		);

		return result[indice];
	};
}
