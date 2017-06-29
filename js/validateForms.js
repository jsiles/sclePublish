/*
Javascript Document
Library Control & validates forms
Design: DEVZONE
www.logoscomunicaciones.com
created: 2008-02-08 by JS
*/
/*valida si el campo esta vacio o no, no toma en cuenta los retorno de carro*/
function is_empty(e)
  {                                    // DECLARACION DE CONSTANTES
    var str = " \n\t" + String.fromCharCode(13); // blancos
                                       // DECLARACION DE VARIABLES
    var i;                             // indice en cadena
    var is_empty;                      // cadena es vacio o no
    for(i = 0, is_empty = true; (i < e.length) && is_empty; i++) // INICIO
      is_empty = str.indexOf(e.charAt(i)) != - 1;
    return(is_empty);
  }

  
  /* dice si cadena es url (http://... ) o no                                     */
function url(e)
  {                                    // DECLARACION DE CONSTANTES
    var http = "http://";              // protocolo HTTP
                                       // DECLARACION DE VARIABLES
    var is_url;                        // cadena es url o no
    if(e.length <= 7)             // INICIO
      is_url = false;                  // no cabe "http://*"
    else
      is_url = http.indexOf(e.substring(0, 7)) != - 1; // lee "http://*"
    return(is_url);
  }


/* salta caracteres alfanumericos y otros a partir de  cadena[i]  y  da  siguiente posicion */
function jump_alphanumeric(e, i, others)
  {                                    // DECLARACION DE VARIABLES
    var j;                             // indice en cadena
    var car;                           // caracter de cadena
    var alphanum;                       // cadena[j] es alfanumerico u otros
    for(j = i, alphanum = true; (j < e.length) && alphanum; j++) // INICIO
      {
        car = e.charAt(j);
        alphanum = is_alphanumeric(car) || (others.indexOf(car) != -1);
      }
    if(!alphanum)                       // lee "alfanumX"
      j--;
    return(j);
  }
  
/* dice si e es alfanumerico                                               */
function is_alphanumeric(e)
  {
    return(is_text(e) || is_number(e));
  }


/* dice si e es alfabetico                                                 */
function is_text(e)               // DECLARACION DE CONSTANTES
  {                                    // caracteres alfabeticos
/*    var alpha = "ABCDEFGHIJKLMNÑOPQRSTUWXYZabcdefghijklmnñopqrstuvwxyz,.:;1234567890/\@!\"$%&()'áéíóú ";
    for (i=0; i<e.length; i++) {
        if (alpha.indexOf(e.charAt(i),0) == -1) return false;
    }*/
    return true;
  }


/* dice si e es numerico                                                   */
function is_number(e)
  {                                    // DECLARACION DE CONSTANTES
    var num = "0123456789";            // caracteres numericos
    for (i=0; i<e.length; i++) {
        if (num.indexOf(e.charAt(i),0) == -1) return false;
    }
    return true;
  }


function is_email(e)
{
    //expresion regular
        var b=/^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/
        
        //comentar la siguiente linea si no se desea que aparezca el alert()
       // alert("Email " + (b.test(txt)?"":"no ") + "válido.")
        
        //devuelve verdadero si validacion OK, y falso en caso contrario
        return b.test(e)
}
  
function restore(e, nameClass)
{
    //document.getElementById('div'+e.id).innerHTML='';
    document.getElementById(e.id).className=nameClass;
    document.getElementById('div'+e.id).style.display='none';
}

function is_restore(e, nameClass)
{
    //document.getElementById('div'+e).innerHTML='';
    document.getElementById(e).className=nameClass;
    document.getElementById('div'+e).style.display='none';
}
function is_validate (e, valType, nameClass, nameClassError, isRequired, msgRequired, msgErrorType) 
{
  is_restore(e,nameClass);
  x = document.getElementById(e).value;
  if (isRequired)
  {
    switch(valType)
    {
        case 'text': 
                    if (is_empty(document.getElementById(e).value)) 
                    {
                       document.getElementById('div'+e).innerHTML=msgRequired;
                       document.getElementById(e).className=nameClassError;
                       document.getElementById('div'+e).style.display=''; 
                       return false;
                    }
                    else
                    {
                        if (is_text(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                 return false;
                                }
                        
                    }
                    break;
        case 'number':
                    if (is_empty(document.getElementById(e).value)) 
                    {
                       document.getElementById('div'+e).innerHTML=msgRequired;
                       document.getElementById(e).className=nameClassError;
                       document.getElementById('div'+e).style.display=''; 
                       return false;
                    }
                    else
                    {
                        if (is_number(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                return false;
                                }
                        
                    }
                    break;
        case 'email':
                     if (is_empty(document.getElementById(e).value)) 
                    {
                       document.getElementById('div'+e).innerHTML=msgRequired;
                       document.getElementById(e).className=nameClassError;
                       document.getElementById('div'+e).style.display=''; 
                       return false;
                    }
                    else
                    {
                        if (is_email(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                return false;
                                }
                        
                    }          
                    break;     
        
    }
  }
  else
  {
   switch(valType)
    {
         case 'text': 
                    if (is_empty(document.getElementById(e).value)) return true;
                    else
                    {
                        if (is_text(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                return false;
                                }
                        
                    }
                    break;
        case 'number':
                    if (is_empty(document.getElementById(e).value)) return true;
                    else
                    {
                        if (is_number(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                return false;
                                }
                        
                    }
                    break;
        case 'email':
                     if (is_empty(document.getElementById(e).value)) return true;
                    else
                    {
                        if (is_email(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                return false;
                                }
                        
                    }
                    break;
                          
    }  
  
  }
}
function isValidate (e, valType, nameClass, nameClassError, isRequired, msgRequired, msgErrorType) 
{
  is_restore(e,nameClass);
  x = document.getElementById(e).value;
  if (isRequired)
  {
    switch(valType)
    {
        case 'text': 
                    if (is_empty(document.getElementById(e).value)) 
                    {
                       if (msgRequired==null) msgRequired=document.getElementById('div'+e).innerHTML;
                       document.getElementById('div'+e).innerHTML=msgRequired;
                       document.getElementById(e).className=nameClassError;
                       document.getElementById('div'+e).style.display=''; 
                       return false;
                    }
                    else
                    {
                        if (is_text(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                 return false;
                                }
                        
                    }
                    break;
        case 'number':
                    if (is_empty(document.getElementById(e).value)) 
                    {
                       if (msgRequired==null) msgRequired=document.getElementById('div'+e).innerHTML;
                       document.getElementById('div'+e).innerHTML=msgRequired;
                       document.getElementById(e).className=nameClassError;
                       document.getElementById('div'+e).style.display=''; 
                       return false;
                    }
                    else
                    {
                        if (is_number(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                return false;
                                }
                        
                    }
                    break;
        case 'email':
                     if (is_empty(document.getElementById(e).value)) 
                    {
                       if (msgRequired==null) msgRequired=document.getElementById('div'+e).innerHTML;
                       document.getElementById('div'+e).innerHTML=msgRequired;
                       document.getElementById(e).className=nameClassError;
                       document.getElementById('div'+e).style.display=''; 
                       return false;
                    }
                    else
                    {
                        if (is_email(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                return false;
                                }
                        
                    }          
                    break;     
        
    }
  }
  else
  {
   switch(valType)
    {
         case 'text': 
                    if (is_empty(document.getElementById(e).value)) return true;
                    else
                    {
                        if (is_text(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                return false;
                                }
                        
                    }
                    break;
        case 'number':
                    if (is_empty(document.getElementById(e).value)) return true;
                    else
                    {
                        if (is_number(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                return false;
                                }
                        
                    }
                    break;
        case 'email':
                     if (is_empty(document.getElementById(e).value)) return true;
                    else
                    {
                        if (is_email(document.getElementById(e).value)) return true;
                        else {
                                document.getElementById('div'+e).innerHTML=msgErrorType;
                                document.getElementById(e).className=nameClassError;
                                document.getElementById('div'+e).style.display=''; 
                                return false;
                                }
                        
                    }
                    break;
                          
    }  
  
  }
}
