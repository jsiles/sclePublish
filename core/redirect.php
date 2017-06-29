<? 	
// se agrega la parte de reconocimiento de ip por pais para poner idioma y moneda en caso de que la use
//$newSession=$_COOKIE["PHPSESSID"];
$first=$_SESSION["first2"];
if ($first=='')
{
//se define la lengua por defecto
			if ($langDefault!='es') $IndexPag = $langDefault.'/'.admin::getDbValue("select col_url from mdl_contents_languages where col_con_uid=1 and col_language='".$langDefault."'").'/';	
			else  {$IndexPag =''; $lang='es';}
			$lang_default=$langDefault;

	// para calculo de valor segun las tablas de la base de datos
	$ips = split ("\.", $_SERVER['REMOTE_ADDR']);
	$valueIp=($ips[3] + $ips[2] * 256 + $ips[1] * 256 * 256 + $ips[0] * 256 * 256 * 256);
	
	$sSQL="select country_code from ip_country where ". $valueIp ." between ipShort_begin and ipShort_end";
	$value = admin::getDbValue($sSQL);
	if ($value)
	{
		  $sSQL = "select language from country_language where code_country='".$value."'";
		  $lang = admin::getDbValue($sSQL);
		  //$sSQL = "select simbol_currency from country_language where code_country='".$value."'";
		  //$currency = admin::getDbValue($sSQL);
			  //if (!$lang&&!$currency)
			  if (!$lang)
			  {
			  	//echo '1';
				$_SESSION["first2"]=$langDefault;
				$lang=$langDefault;
				header('Location: '.$domain.'/'.$IndexPag);
			  }
			  else 
			  {
			  	//echo '2';
				$_SESSION["first2"]=$langDefault;		  
				if ($lang!='es') {$IndexPag = $lang.'/'.admin::getDbValue("select col_url from mdl_contents_languages where col_con_uid=1 and col_language='".$lang."'").'/'; }
				else  {$IndexPag =''; $lang=$langDefault;}
				$lang_default=$lang;
				header('Location: '.$domain.'/'.$IndexPag);
			  }
		  }
	else {
			//echo '3'.$lang;
			$_SESSION["first2"]=$langDefault;
			if ($lang!='es') {$IndexPag = $langDefault.'/'.admin::getDbValue("select col_url from mdl_contents_languages where col_con_uid=1 and col_language='".$langDefault."'").'/'; }
			else  {$IndexPag =''; $lang='es';}
			header('Location: '.$domain.'/'.$IndexPag);
			//admin::setSession("CURRENCY",'do');
			//admin::setSession("COUNTRY",'BO');
	}
}
else 
{
	$langUrl=admin::getDBvalue("select lan_code FROM sys_language where lan_code='".admin::toSql($urlARRAY[$urlPositionTitle],'String')."'");
	if ($langUrl)
	{
			$lang=$langUrl;
			admin::setSession("LANG",$langUrl);
			$urlLangAux=$lang.'/';
	}
	else
	{
			$lang='es';
			admin::setSession("LANG",$lang);
			$urlLangAux='';
	}
}
?>