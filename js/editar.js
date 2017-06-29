// Javascript Module
function passChange()
{
	$("#password1").hide();
	$("#password2").show();
}
function passChange2()
{
	$("#password1").show();
	document.getElementById('password').value='';
	$("#password2").hide();
}


// hide message 
  if($('#messageBox').html()!='' )
	  $('#messageBox').fadeIn(800, function() {
	          setTimeout(function(){ $('#messageBox').fadeOut(800) },4000);
	      });
//validar registro
function validateForm3()
{
	sw0=true;
	sw1=true;
	sw2=true;
	sw3=true;
	sw4=true;

	//is_validate (e, valType, nameClass, nameClassError, isRequired, msgRequired, msgErrorType)
	sw0 = is_validate('email', 'text', 'input', 'inputError', true, 'Este es un campo requerido', 'Solo texto');
	if (sw0==false)
	{
	document.getElementById('email').className='req';
	$('#email2').show();
	}
	
	sw2 = is_validate('firstname', 'text', 'input', 'inputError', true, 'Este es un campo requerido', 'Solo texto');
	if (sw2==false)
	{
	document.getElementById('firstname').className='req';
	$('#firstname2').show();
	}
	sw3 = is_validate('lastname', 'text', 'input', 'inputError', true, 'Este es un campo requerido', 'Solo texto');
	if (sw3==false)
	{
	document.getElementById('lastname').className='req';
	$('#lastname2').show();
	}
	sw4 = is_validate('phone', 'number', 'input', 'inputError', true, 'Este es un campo requerido', 'Solo números');
	if (sw4==false)
	{
	document.getElementById('phone').className='req';
	$('#phone2').show();
	}
	//alert ('sw0'+sw0+'sw1'+sw1+'sw2'+sw2+'sw3'+sw3);
	
	if ((sw0==true) && (sw1==true) && (sw2==true)  && (sw3==true)  && (sw4==true))
	{
		var domain = document.getElementById('domain');
		document.getElementById('cOk').value='ok';
		$("#formA").attr('action',domain.value+"/code/editUser.php");
		document.formA.submit();
	}
	else
	{
		document.getElementById('cOk').value='';
	}
}
function restoreNew(a)
{
 if (document.getElementById(a).className=='req')
 {
	 $("#div"+a).hide();
	 $("#"+a+'2').hide();
	 document.getElementById(a).className='inputB';
 }
}