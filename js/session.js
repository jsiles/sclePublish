// JavaScript Document
function login()
{
usernameClient = $("#usernameClient").val();
passwordClient = $("#passwordClient").val();

$.ajax({
   type: "POST",
   url: SERVER+"/code/session.php",
   data: "usernameClient="+usernameClient+"&passwordClient="+passwordClient,
   success: function(msg){
     document.location.href=msg;
   }
 });
	return false;
}
function altas(ruta)
{
	pass2 = $("#pass2").val();
	pass = $("#pass").val();
	idUser = $("#idUser").val();
	msg='Usuario y contrase&ntilde;a requeridos.';
        sw=true;
        if(pass.length<8){
         msg='Contrase&ntilde;a debe tener m&iacute;nimo 8 caracteres.';
         sw=false;
        }
        if(pass!=pass2){
         msg='La Nueva Contrase&ntilde;a debe ser la misma.';
         sw=false;
        }

	if((idUser.length>0)&&(pass.length>0)&&(pass2.length>0)&&(sw))
	{
		$.ajax({
   			type: "POST",
   			url: SERVER+"/code/userAdd.php",
  			data: "pass="+pass+"&pass2="+pass2+"&idUser="+idUser,
   			success: function(msg2){
	     		$("#message").html(msg2);
				msg=msg2;
   			},
			complete: function(data) {
				if (msg.substring(0,3)=='Act'){
					setTimeout(function () {
					   window.location.href = ruta;
					}, 100);
				}
				else{
					setTimeout(function () {
					   $("#message").show();
					}, 1000);
				}
        	}
 		});
	}else{
		$("#message").html(msg);
	}
}