// Javascript Module

// hide message 
  if($('#messageBox').html()!='' )
	  $('#messageBox').fadeIn(800, function() {
	          setTimeout(function(){ $('#messageBox').fadeOut(800) },4000);
	      });
//validar registro
function validateForm2()
{
	sw0=true;
	sw1=true;
	sw2=true;
	sw3=true;
	sw4=true;
	sw5=true;
	
	//sw3=true;
	//is_validate (e, valType, nameClass, nameClassError, isRequired, msgRequired, msgErrorType)
	sw0 = is_validate('login', 'text', 'input', 'inputError', true, 'Este es un campo requerido', 'Solo texto');
	if (sw0==false)
	{
	document.getElementById('login').className='req';
	$('#login2').show();
	}
	sw1 = is_validate('password', 'text', 'input', 'inputError', true, 'Este es un campo requerido', 'Solo texto');
	if (sw1==false)
	{
	document.getElementById('password').className='req';
	$('#password2').show();
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
	sw4 = is_validate('email', 'text', 'input', 'inputError', true, 'Este es un campo requerido', 'Solo texto');
	if (sw4==false)
	{
	document.getElementById('email').className='req';
	$('#email2').show();
	}
	sw5 = is_validate('phone', 'number', 'input', 'inputError', true, 'Este es un campo requerido', 'Solo números');
	if (sw5==false)
	{
	document.getElementById('phone').className='req';
	$('#phone2').show();
	}
	//alert ('sw0'+sw0+'sw1'+sw1+'sw2'+sw2+'sw3'+sw3);
	
	if ((sw0==true) && (sw1==true) && (sw2==true)  && (sw3==true)  && (sw4==true)  && (sw5==true))
	{
		var domain = document.getElementById('domain');
		document.getElementById('cOk').value='ok';
		$("#formA").attr('action',domain.value+"/code/registroAdd.php");
		document.formA.submit();
	}
	else
	{
		document.getElementById('cOk').value='';
	}
}