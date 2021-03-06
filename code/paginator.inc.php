<?php
 if(empty($_pagi_sql)){
	//Si no se defini� $_pagi_sql... error!
	//Este error se muestra s� o s� (ya que no es un error de mysql)
	die("<b>Error Paginator: </b>No se ha definido la variable \$_pagi_sql");
 }
 
 if(empty($_pagi_cuantos)){
	//Si no se ha especificado la cantidad de registros por p�gina
	//$_pagi_cuantos ser� por defecto 20
	$_pagi_cuantos = 20;
 }
 
 if(!isset($_pagi_mostrar_errores)){
	//Si no se ha elegido si se mostrar� o no errores
	//$_pagi_errores ser� por defecto true. (se muestran los errores)
	$_pagi_mostrar_errores = true;
 }

/*
 * Establecimiento de la p�gina actual.
 *------------------------------------------------------------------------
 */
 if (empty(admin::getParam('_pagi_pg'))){
	//Si no se ha hecho click a ninguna p�gina espec�fica
	//O sea si es la primera vez que se ejecuta el script
    //$_pagi_actual es la pagina actual-->ser� por defecto la primera.
	$_pagi_actual = 1;
 }else{
	//Si se "pidi�" una p�gina espec�fica:
	//La p�gina actual ser� la que se pidi�.
    $_pagi_actual = admin::getParam('_pagi_pg');
 }
//------------------------------------------------------------------------


/*
 * Establecimiento del n�mero de p�ginas y del total de registros.
 *------------------------------------------------------------------------
 */
 //Contamos el total de registros en la BD (para saber cu�ntas p�ginas ser�n)
 $query=$pagDb->query($_pagi_sql);
// $_pagi_sqlConta = eregi_replace("select (.*) from", "SELECT COUNT(*) FROM" );
 $_pagi_totalReg = $pagDb->numrows();
 //Si ocurri� error y mostrar errores est� activado
 if($_pagi_totalReg == false && $_pagi_mostrar_errores == true){
	//die (" Error en la consulta de conteo de registros. Mysql dijo: <b>".mysql_error()."</b>");
	//echo ("<font face=arial>NO SE ENCONTRARON RESULTADOS</font>");
 }
 //$_pagi_totalReg = mysql_result($_pagi_result2,0,0);//total de registros
 
 //Calculamos el n�mero de p�ginas (saldr� un decimal)
 //con ceil() redondeamos y $_pagi_totyalPags ser� el n�mero total (entero) de p�ginas que tendremos
 $_pagi_totalPags = ceil($_pagi_totalReg / $_pagi_cuantos);

 //La variable $_pagi_navegacion contendr� los enlaces a las p�ginas.
 $_pagi_navegacion = '';
 if ($_pagi_actual != 1){
	//Si no estamos en la p�gina 1. Ponemos el enlace "anterior"
	//$_pagi_url = $_pagi_actual - 1;//ser� el n�mero de p�gina al que enlazamos
	//$_pagi_navegacion .= "<li><a href='".$_pagi_enlace."_pagi_pg=".$_pagi_url."'>Anterior</a></li>";
 }
 
 //La variable $_pagi_nav_num_enlaces sirve para definir cu�ntos enlaces con 
 //n�meros de p�gina se mostrar�n como m�ximo.
 //Ojo: siempre se mostrar� un n�mero impar de enlaces. M�s info en la documentaci�n.
 
 if(!isset($_pagi_nav_num_enlaces)){
	//Si no se defini� la variable $_pagi_nav_num_enlaces
	//Se asume que se mostrar�n todos los n�meros de p�gina en los enlaces.
	$_pagi_nav_desde = 1;//Desde la primera
	$_pagi_nav_hasta = $_pagi_totalPags;//hasta la �ltima
 }else{
	//Si se defini� la variable $_pagi_nav_num_enlaces
	//Calculamos el intervalo para restar y sumar a partir de la p�gina actual
	$_pagi_nav_intervalo = ceil($_pagi_nav_num_enlaces/2) - 1;
	
	//Calculamos desde qu� n�mero de p�gina se mostrar�
	$_pagi_nav_desde = $_pagi_actual - $_pagi_nav_intervalo;
	//Calculamos hasta qu� n�mero de p�gina se mostrar�
	$_pagi_nav_hasta = $_pagi_actual + $_pagi_nav_intervalo;
	
	//Ajustamos los valores anteriores en caso sean resultados no v�lidos
	
	//Si $_pagi_nav_desde es un n�mero negativo
	if($_pagi_nav_desde < 1){
		//Le sumamos la cantidad sobrante al final para mantener el n�mero de enlaces que se quiere mostrar. 
		$_pagi_nav_hasta -= ($_pagi_nav_desde - 1);
		//Establecemos $_pagi_nav_desde como 1.
		$_pagi_nav_desde = 1;
	}
	//Si $_pagi_nav_hasta es un n�mero mayor que el total de p�ginas
	if($_pagi_nav_hasta > $_pagi_totalPags){
		//Le restamos la cantidad excedida al comienzo para mantener el n�mero de enlaces que se quiere mostrar.
		$_pagi_nav_desde -= ($_pagi_nav_hasta - $_pagi_totalPags);
		//Establecemos $_pagi_nav_hasta como el total de p�ginas.
		$_pagi_nav_hasta = $_pagi_totalPags;
		//Hacemos el �ltimo ajuste verificando que al cambiar $_pagi_nav_desde no haya quedado con un valor no v�lido.
		if($_pagi_nav_desde < 1){
			$_pagi_nav_desde = 1;
		}
	}
 }
if ($_pagi_nav_hasta>1){
 for ($_pagi_i = $_pagi_nav_desde; $_pagi_i<=$_pagi_nav_hasta; $_pagi_i++){//Desde p�gina 1 hasta �ltima p�gina ($_pagi_totalPags)
    if ($_pagi_i == $page_list) {
		//Si el n�mero de p�gina es la actual ($_pagi_actual). Se escribe el n�mero, pero sin enlace y en negrita.
		if ($_pagi_nav_hasta!=1)
			{
	        $_pagi_navegacion .= "<li ><a href='' class='pagAct'>" . $_pagi_i . "</a></li>";
			}
    }else{
		//Si es cualquier otro. Se escibe el enlace a dicho n�mero de p�gina.
		if ($_pagi_actual==$_pagi_i) $classPag='class="pagAct"';
		else $classPag='';
        $_pagi_navegacion .= "<li><a href='".$urlFront.$_pagi_i."/' ".$classPag.">".$_pagi_i."</a></li>";
    }
 }
}
 if ($_pagi_actual < $_pagi_totalPags){
	//Si no estamos en la �ltima p�gina. Ponemos el enlace "Siguiente"
    //$_pagi_url = $_pagi_actual + 1;//ser� el n�mero de p�gina al que enlazamos
    //$_pagi_navegacion .= "&nbsp;de&nbsp;$_pagi_totalPags&nbsp;<a href='".$_pagi_enlace."_pagi_pg=".$_pagi_url."'>Siguiente</a>";
 }

//------------------------------------------------------------------------
/*
 * Obtenci�n de los registros que se mostrar�n en la p�gina actual.
 *------------------------------------------------------------------------
 */
 //Calculamos desde qu� registro se mostrar� en esta p�gina
 //Recordemos que el conteo empieza desde CERO.
 $_pagi_inicial = ($_pagi_actual-1) * $_pagi_cuantos;
 
 //Consulta SQL. Devuelve $cantidad registros empezando desde $_pagi_inicial
 $_pagi_sqlLim = $_pagi_sql." LIMIT $_pagi_inicial,$_pagi_cuantos";
 $_pagi_result = $pagDb->query($_pagi_sqlLim);
 //Si ocurri� error y mostrar errores est� activado
 if($_pagi_result == false && $_pagi_mostrar_errores == true){
 	//die ("Error en la consulta limitada. Mysql dijo: <b>".mysql_error()."</b>");
 }

//------------------------------------------------------------------------


/*
 * Generaci�n de la informaci�n sobre los registros mostrados.
 *------------------------------------------------------------------------
 */
 //N�mero del primer registro de la p�gina actual
 $desde = $_pagi_inicial + 1;
 
 //N�mero del �ltimo registro de la p�gina actual
 $hasta = $_pagi_inicial + $_pagi_cuantos;
 if($hasta > $_pagi_totalReg){
 	//Si estamos en la �ltima p�gina
	//El ultimo registro de la p�gina actual ser� igual al n�mero de registros.
 	$hasta = $_pagi_totalReg;
 }
 
 //$_pagi_info = "desde el $desde hasta el $hasta de un total de $_pagi_totalReg";

//------------------------------------------------------------------------


/**
 * Variables que quedan disponibles despu�s de incluir el script v�a include():
 * ------------------------------------------------------------------------
 
 * $_pagi_result			Identificador del resultado de la consulta a la BD para los registros de la p�gina actual. 
 							Listo para ser "pasado" por una funci�n como mysql_fetch_row(), mysql_fetch_array(), 
							mysql_fetch_assoc(), etc.
							
 * $_pagi_navegacion		Cadena que contiene la barra de navegaci�n con los enlaces a las diferentes p�ginas.
 							Ejemplo: "<<anterior 1 2 3 4 siguiente>>".
							
 * $_pagi_info				Cadena que contiene informaci�n sobre los registros de la p�gina actual.
 							Ejemplo: "desde el 16 hasta el 30 de un total de 123";				

*/
?>