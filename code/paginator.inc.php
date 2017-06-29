<?php
 if(empty($_pagi_sql)){
	//Si no se definió $_pagi_sql... error!
	//Este error se muestra sí o sí (ya que no es un error de mysql)
	die("<b>Error Paginator: </b>No se ha definido la variable \$_pagi_sql");
 }
 
 if(empty($_pagi_cuantos)){
	//Si no se ha especificado la cantidad de registros por página
	//$_pagi_cuantos será por defecto 20
	$_pagi_cuantos = 20;
 }
 
 if(!isset($_pagi_mostrar_errores)){
	//Si no se ha elegido si se mostrará o no errores
	//$_pagi_errores será por defecto true. (se muestran los errores)
	$_pagi_mostrar_errores = true;
 }

/*
 * Establecimiento de la página actual.
 *------------------------------------------------------------------------
 */
 if (empty(admin::getParam('_pagi_pg'))){
	//Si no se ha hecho click a ninguna página específica
	//O sea si es la primera vez que se ejecuta el script
    //$_pagi_actual es la pagina actual-->será por defecto la primera.
	$_pagi_actual = 1;
 }else{
	//Si se "pidió" una página específica:
	//La página actual será la que se pidió.
    $_pagi_actual = admin::getParam('_pagi_pg');
 }
//------------------------------------------------------------------------


/*
 * Establecimiento del número de páginas y del total de registros.
 *------------------------------------------------------------------------
 */
 //Contamos el total de registros en la BD (para saber cuántas páginas serán)
 $query=$pagDb->query($_pagi_sql);
// $_pagi_sqlConta = eregi_replace("select (.*) from", "SELECT COUNT(*) FROM" );
 $_pagi_totalReg = $pagDb->numrows();
 //Si ocurrió error y mostrar errores está activado
 if($_pagi_totalReg == false && $_pagi_mostrar_errores == true){
	//die (" Error en la consulta de conteo de registros. Mysql dijo: <b>".mysql_error()."</b>");
	//echo ("<font face=arial>NO SE ENCONTRARON RESULTADOS</font>");
 }
 //$_pagi_totalReg = mysql_result($_pagi_result2,0,0);//total de registros
 
 //Calculamos el número de páginas (saldrá un decimal)
 //con ceil() redondeamos y $_pagi_totyalPags será el número total (entero) de páginas que tendremos
 $_pagi_totalPags = ceil($_pagi_totalReg / $_pagi_cuantos);

 //La variable $_pagi_navegacion contendrá los enlaces a las páginas.
 $_pagi_navegacion = '';
 if ($_pagi_actual != 1){
	//Si no estamos en la página 1. Ponemos el enlace "anterior"
	//$_pagi_url = $_pagi_actual - 1;//será el número de página al que enlazamos
	//$_pagi_navegacion .= "<li><a href='".$_pagi_enlace."_pagi_pg=".$_pagi_url."'>Anterior</a></li>";
 }
 
 //La variable $_pagi_nav_num_enlaces sirve para definir cuántos enlaces con 
 //números de página se mostrarán como máximo.
 //Ojo: siempre se mostrará un número impar de enlaces. Más info en la documentación.
 
 if(!isset($_pagi_nav_num_enlaces)){
	//Si no se definió la variable $_pagi_nav_num_enlaces
	//Se asume que se mostrarán todos los números de página en los enlaces.
	$_pagi_nav_desde = 1;//Desde la primera
	$_pagi_nav_hasta = $_pagi_totalPags;//hasta la última
 }else{
	//Si se definió la variable $_pagi_nav_num_enlaces
	//Calculamos el intervalo para restar y sumar a partir de la página actual
	$_pagi_nav_intervalo = ceil($_pagi_nav_num_enlaces/2) - 1;
	
	//Calculamos desde qué número de página se mostrará
	$_pagi_nav_desde = $_pagi_actual - $_pagi_nav_intervalo;
	//Calculamos hasta qué número de página se mostrará
	$_pagi_nav_hasta = $_pagi_actual + $_pagi_nav_intervalo;
	
	//Ajustamos los valores anteriores en caso sean resultados no válidos
	
	//Si $_pagi_nav_desde es un número negativo
	if($_pagi_nav_desde < 1){
		//Le sumamos la cantidad sobrante al final para mantener el número de enlaces que se quiere mostrar. 
		$_pagi_nav_hasta -= ($_pagi_nav_desde - 1);
		//Establecemos $_pagi_nav_desde como 1.
		$_pagi_nav_desde = 1;
	}
	//Si $_pagi_nav_hasta es un número mayor que el total de páginas
	if($_pagi_nav_hasta > $_pagi_totalPags){
		//Le restamos la cantidad excedida al comienzo para mantener el número de enlaces que se quiere mostrar.
		$_pagi_nav_desde -= ($_pagi_nav_hasta - $_pagi_totalPags);
		//Establecemos $_pagi_nav_hasta como el total de páginas.
		$_pagi_nav_hasta = $_pagi_totalPags;
		//Hacemos el último ajuste verificando que al cambiar $_pagi_nav_desde no haya quedado con un valor no válido.
		if($_pagi_nav_desde < 1){
			$_pagi_nav_desde = 1;
		}
	}
 }
if ($_pagi_nav_hasta>1){
 for ($_pagi_i = $_pagi_nav_desde; $_pagi_i<=$_pagi_nav_hasta; $_pagi_i++){//Desde página 1 hasta última página ($_pagi_totalPags)
    if ($_pagi_i == $page_list) {
		//Si el número de página es la actual ($_pagi_actual). Se escribe el número, pero sin enlace y en negrita.
		if ($_pagi_nav_hasta!=1)
			{
	        $_pagi_navegacion .= "<li ><a href='' class='pagAct'>" . $_pagi_i . "</a></li>";
			}
    }else{
		//Si es cualquier otro. Se escibe el enlace a dicho número de página.
		if ($_pagi_actual==$_pagi_i) $classPag='class="pagAct"';
		else $classPag='';
        $_pagi_navegacion .= "<li><a href='".$urlFront.$_pagi_i."/' ".$classPag.">".$_pagi_i."</a></li>";
    }
 }
}
 if ($_pagi_actual < $_pagi_totalPags){
	//Si no estamos en la última página. Ponemos el enlace "Siguiente"
    //$_pagi_url = $_pagi_actual + 1;//será el número de página al que enlazamos
    //$_pagi_navegacion .= "&nbsp;de&nbsp;$_pagi_totalPags&nbsp;<a href='".$_pagi_enlace."_pagi_pg=".$_pagi_url."'>Siguiente</a>";
 }

//------------------------------------------------------------------------
/*
 * Obtención de los registros que se mostrarán en la página actual.
 *------------------------------------------------------------------------
 */
 //Calculamos desde qué registro se mostrará en esta página
 //Recordemos que el conteo empieza desde CERO.
 $_pagi_inicial = ($_pagi_actual-1) * $_pagi_cuantos;
 
 //Consulta SQL. Devuelve $cantidad registros empezando desde $_pagi_inicial
 $_pagi_sqlLim = $_pagi_sql." LIMIT $_pagi_inicial,$_pagi_cuantos";
 $_pagi_result = $pagDb->query($_pagi_sqlLim);
 //Si ocurrió error y mostrar errores está activado
 if($_pagi_result == false && $_pagi_mostrar_errores == true){
 	//die ("Error en la consulta limitada. Mysql dijo: <b>".mysql_error()."</b>");
 }

//------------------------------------------------------------------------


/*
 * Generación de la información sobre los registros mostrados.
 *------------------------------------------------------------------------
 */
 //Número del primer registro de la página actual
 $desde = $_pagi_inicial + 1;
 
 //Número del último registro de la página actual
 $hasta = $_pagi_inicial + $_pagi_cuantos;
 if($hasta > $_pagi_totalReg){
 	//Si estamos en la última página
	//El ultimo registro de la página actual será igual al número de registros.
 	$hasta = $_pagi_totalReg;
 }
 
 //$_pagi_info = "desde el $desde hasta el $hasta de un total de $_pagi_totalReg";

//------------------------------------------------------------------------


/**
 * Variables que quedan disponibles después de incluir el script vía include():
 * ------------------------------------------------------------------------
 
 * $_pagi_result			Identificador del resultado de la consulta a la BD para los registros de la página actual. 
 							Listo para ser "pasado" por una función como mysql_fetch_row(), mysql_fetch_array(), 
							mysql_fetch_assoc(), etc.
							
 * $_pagi_navegacion		Cadena que contiene la barra de navegación con los enlaces a las diferentes páginas.
 							Ejemplo: "<<anterior 1 2 3 4 siguiente>>".
							
 * $_pagi_info				Cadena que contiene información sobre los registros de la página actual.
 							Ejemplo: "desde el 16 hasta el 30 de un total de 123";				

*/
?>