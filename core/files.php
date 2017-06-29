<?php
class classfile
{
  var $Months;

  function uploadFile( $arrFile = NULL, $path, $file){    	
	 	//if(!is_dir($path)) classfile::createPath($path);	 			 	
	  $nombre 		= $arrFile['name'];
	  $nombreTemporal = $arrFile['tmp_name'];		  
	  if( $nombre != '')
	  {
	   move_uploaded_file($nombreTemporal , $path . $file);
	   //chmod( $path . $file , 0666 );
	  }
	  return;
	}
/*	function createPath($path){ 
	 	$pathSubStr = $path; //substr($path,3);
	 	$pathExplode = explode("/",$pathSubStr);
	 	if(is_array($pathExplode))
		 	foreach($pathExplode as $key => $value){
		 		$createPath .= "/".$value;
		 		if(!is_dir($createPath)){	
				   mkdir( $createPath, 0777 );
				   chmod( $createPath, 0777 );
				   chmod( $path, 0666 );
		 		}
		 	}
	 	return;
	}
*/	
/*	function createPath2($path){ 
	 	$createPath = $path; 
		if(!is_dir($createPath))
			{	
		   	mkdir( $createPath, 0777 );
		   	chmod( $createPath, 0777 );
		   	chmod( $path, 0666 );
			}
	 	return;
	}*/
	function verifyPermission($permissionUID) {
	  if (!isset($_SESSION)) {
	    echo "<script language='javascript'>document.location.href='../login/login?Message=2';</script>";
		//header('location:../login/login?Message=2');
	    die;
	  }
    $Conexion1 = new Conexion();
    $Conexion1->setear($Conexion1);
    $Conexion1->Ejecutar("SELECT * FROM PERMISSIONS AS P LEFT JOIN ROLE_PERMISSION AS RP ON (P.UID = RP.UID_PERMISSION) LEFT JOIN ROLES AS R ON (RP.UID_ROLE = R.UID) LEFT JOIN USER_ROLE AS UR ON (R.UID = UR.UID_ROLE) LEFT JOIN USERS AS U ON (UR.UID_USER = U.UID) WHERE P.PER_STATUS = 'ACTIVE' AND R.ROL_STATUS = 'ACTIVE' AND U.USER_STATUS = 'ACTIVE' AND P.UID = " .  $permissionUID. " AND U.UID = " . (int)$_SESSION['USER_LOGGED']);
    $NumeroRegistros1 = $Conexion1->contar();
    if ($NumeroRegistros1 == 0) {
	echo "<script language='javascript'>document.location.href='../login/login?Message=3';</script>";
      //header('location:../login/login?Message=3');
			die;                                     
    }
  }
  
  function getPermissions() {
    $Conexion1 = new Conexion();
    $Conexion1->setear($Conexion1);
    $Conexion1->Ejecutar("SELECT P.UID AS UID_PERMISSION FROM USERS AS U LEFT JOIN USER_ROLE AS UR ON (U.UID = UR.UID_USER) LEFT JOIN ROLES AS R ON (UR.UID_ROLE = R.UID) LEFT JOIN ROLE_PERMISSION AS RP ON (R.UID = RP.UID_ROLE) LEFT JOIN PERMISSIONS AS P ON (RP.UID_PERMISSION = P.UID) WHERE U.USER_STATUS = 'ACTIVE' AND R.ROL_STATUS = 'ACTIVE' AND P.PER_STATUS = 'ACTIVE' AND U.UID = " . (int)$_SESSION['USER_LOGGED']);
    $NumeroRegistros1 = $Conexion1->contar();
    for ($i=0; $i<$NumeroRegistros1; $i++) {
      $Datos = $Conexion1->leer();
      $Permissions[] = $Datos['UID_PERMISSION'];
    }
    return $Permissions;
  }

  function loadMonths() {
    //Español
    $months['es'][1]  = 'Enero';
    $months['es'][2]  = 'Febrero';
    $months['es'][3]  = 'Marzo';
    $months['es'][4]  = 'Abril';
    $months['es'][5]  = 'Mayo';
    $months['es'][6]  = 'Junio';
    $months['es'][7]  = 'Julio';
    $months['es'][8]  = 'Agosto';
    $months['es'][9]  = 'Septiembre';
    $months['es'][10] = 'Octubre';
    $months['es'][11] = 'Noviembre';
    $months['es'][12] = 'Diciembre';
    //Inglés
    $months['en'][1]  = 'January';
    $months['en'][2]  = 'February';
    $months['en'][3]  = 'March';
    $months['en'][4]  = 'April';
    $months['en'][5]  = 'May';
    $months['en'][6]  = 'June';
    $months['en'][7]  = 'July';
    $months['en'][8]  = 'August';
    $months['en'][9]  = 'September';
    $months['en'][10] = 'October';
    $months['en'][11] = 'November';
    $months['en'][12] = 'December';
    return $months;
  }

  function loadDays() {
    //Español
    $days['es'][0]  = 'Domingo';
    $days['es'][1]  = 'Lunes';
    $days['es'][2]  = 'Martes';
    $days['es'][3]  = 'Miercoles';
    $days['es'][4]  = 'Jueves';
    $days['es'][5]  = 'Viernes';
    $days['es'][6]  = 'Sábado';
    //Inglés
    $days['en'][0]  = 'Sunday';
    $days['en'][1]  = 'Monday';
    $days['en'][2]  = 'Tuesday';
    $days['en'][3]  = 'Wednesday';
    $days['en'][4]  = 'Thursday';
    $days['en'][5]  = 'Friday';
    $days['en'][6]  = 'Saturday';
    return $days;
  }

  function renderDateField($name, $anioInicial = 1900, $anioFinal = 2010, $value = '', $language = 'es') {
    if ($value != '') {
      $Aux = explode('-', $value);
      $anio  = $Aux[0];
      $month = $Aux[1];
      $day   = $Aux[2];
    }
    $yearDateField = '<select name="' . $name . '[YEAR]" id="' . $name . '[YEAR]"><option value=""></option>';
    for ($i=$anioInicial; $i<=$anioFinal; $i++)
      $yearDateField .= '<option value="' . $i . '"' . ($i==(int)$anio?' selected="selected"':'') . '>' . $i . '</option>';
    $yearDateField .= '</select>';
    $months = classfile::loadMonths();
    if (($language != 'es') && ($language != 'en'))
      $language = 'en';
    $monthDateField = '<select name="' . $name . '[MONTH]" id="' . $name . '[MONTH]"><option value=""></option>';
    for ($i=1; $i<=12; $i++)
      $monthDateField .= '<option value="' . $i . '"' . ($i==(int)$month?' selected="selected"':'') . '>' . $months[$language][$i] . '</option>';
    $monthDateField .= '</select>';
    $dayDateField = '<select name="' . $name . '[DAY]" id="' . $name . '[DAY]"><option value=""></option>';
    for ($i=1; $i<=31; $i++)
      $dayDateField .= '<option value="' . $i . '"' . ($i==(int)$day?' selected="selected"':'') . '>' . $i . '</option>';
    $dayDateField .= '</select>';
    $additional  = '<input type="reset" value=" ... " id="_btn_' . $name . '_"><div id="_div_' . $name . '_"></div>';
    $additional .= '<script type="text/javascript">
                      var _objcal_' . $name . '_ = new Zapatec.Calendar.setup({
                        inputField: "_div_' . $name . '_",
				                ifFormat  : "%Y-%m-%d",
				                button    : "_btn_' . $name . '_",
				                onUpdate  : _fun_' . $name . '_,
				                showsTime : false
		                  });
		                  function _fun_' . $name . '_(_objcal_' . $name . '_) {
		                    var date = _objcal_' . $name . '_.date;
		                    var selectMonth = document.getElementById("' . $name . '[MONTH]");
		                    selectMonth.selectedIndex = (date.getMonth() + 1);
		                    var selectDay = document.getElementById("' . $name . '[DAY]");
		                    selectDay.selectedIndex = (date.getDate());
		                    var selectYear = document.getElementById("' . $name . '[YEAR]");
		                    for (_i_=0; _i_<selectYear.length; _i_++) {
		                      var _year_ = new String(date.getYear());
		                      if (_year_.length == 2)
		                        _year_ = "19" + _year_;
		                      if (selectYear[_i_].value == _year_)
		                        selectYear.selectedIndex = _i_;
		                    }
		                  }
	                  </script>';
    switch ($language) {
      case 'es':
        return $dayDateField . $monthDateField . $yearDateField . $additional;
      break;
      case 'en';
        return $monthDateField . $dayDateField . $yearDateField . $additional;
      break;
      default:
        return $monthDateField . $dayDateField . $yearDateField . $additional;
      break;
    }
  }

  function dateFormat($date, $language = 'es') {
    $date   = str_replace(' 00:00:00', '', $date);
    if (strlen($date) < 10) return '';
    $months = classfile::loadMonths();
    $aux    = explode('-', $date);
    switch ($language) {
      case 'es':
        $date = (int)$aux[2] . ' de ' . $months[$language][(int)$aux[1]] . ' del ' . $aux[0];
      break;
      case 'en':
        $date = $months[$language][(int)$aux[1]] . ' ' . (int)$aux[2] . ' of ' . $aux[0];
      break;
      default:
        $date = (int)$aux[2] . ' de ' . $months[$language][(int)$aux[1]] . ' del ' . $aux[0];
      break;
    }
    return $date;
  }

}

?>