// Javascript Module


function delFromFav(new_uid){
	//$('#favs').load('../code/addFavorites.php?new_uid='+new_uid);
	
		  $.ajax({
				url: '../code/delFavorites.php',
				data: 'new_uid='+new_uid,
				complete : function(ob){
								//$("#favs").html(ob.responseText);
								$('#gotoFav').load('../code/gotoFavs.php');
								$('#li_'+new_uid).remove();
							},
			 			error: function(objeto){
            						alert("Pasó lo siguiente: "+objeto.responseText);
        						}/*,
						success: function(datos){
            						alert('resultado='+datos);
        						 }*/	
	 });
	 
}
