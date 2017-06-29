// JavaScript Document 
// Validate with jquery version 1.3
// more validations added and uses name instead if div

	var filters = {
			required: function(el) {return ($(el).val() != '' && $(el).val() != -1);},
			email: function(el) {return /^[A-Za-z][A-Za-z0-9_\.\-]*@[A-Za-z0-9_\-]+\.[A-Za-z0-9_.\-]+[A-za-z]$/.test($(el).val());},
			username: function(el) {return /^[A-Za-z][A-Za-z0-9_\.-]{3,27}$/.test($(el).val());},
			password: function(el) {return /^[A-Za-z0-9]{5,28}$/.test($(el).val());},			
			phone: function(el){return /^[0-9 -]*$/.test($(el).val());},
			numeric: function(el){return /^[0-9]*\.?[0-9]*$/.test($(el).val());},
			currency: function(el){return /^[0-9]*\.?[0-9]{0,2}$/.test($(el).val());},
			alpha: function(el){return /^[a-zA-Z áéíóúAÉÍÓÚÑñ\.,;:\|)"(º_@><#&'\?¿¡!/\\%\$=]*$/.test($(el).val());},
			alphanum: function(el){return /^[a-zA-Z0-9 áéíóúAÉÍÓÚÑñ\.,;:\|)"(º_@><#&'\?¿¡!/\\%\$=]*$/.test($(el).val());}
		};	


$.extend({
		/* PARAMOS LA EJECUCIÓN*/
		stop: function(e){
        if (e.preventDefault) e.preventDefault();
        if (e.stopPropagation) e.stopPropagation();
    }, 
    /* PERSONALIZAMOS LA SALIDA POR PANTALLA */
    alert: function(str) {
    	alert(str);	
    }
});


$(document).ready(function(){

//on submit
	$("form.validable").bind("submit", function(e){
		if (typeof filters == 'undefined') return;
	    $(this).find("input, textarea, select").each(function(x,el){ 
	        if ($(el).attr("className") != 'undefined') { 
				$(el).removeClass("req");
	        	$.each(new String($(el).attr("className")).split(" "), function(x, klass){
	            if ($.isFunction(filters[klass]))
	                if (!filters[klass](el)){
						$(el).addClass("req");
						var idName = $(el).attr("name");
                        if($(el).val()==''){
                            $("#div_"+idName).show();
						}
                        else{
                            $("#div_"+idName+"_2").show();
						}
					}
	        });
	        }
	    });
		
		if ( ($(this).find(".req").size() > 0) || $(this).attr('action')=='' ) {
			$.stop(e || window.event);
			return false;
		}
	    return true;
	});

// on focus	remueve los tag de error
	$("form.validable").find("input, textarea, select").each(function(x,el){ 
		$(el).bind("focus",function(e){
				if ($(el).attr("className") != 'undefined') { 
								$(el).removeClass("req");
						var idName = $(el).attr("name");
						$("#div_"+idName).hide();
						$("#div_"+idName+"_2").hide();
				}
		});
	});
// end

});
	