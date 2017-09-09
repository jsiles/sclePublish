<?php
include_once ("path.php");
require_once("safeHtml.php");
define("VERSION","1.3");
$app_path = ".";
$labels = array(); 
$linksLabels = array();
$labelsMenu='';
$labelsSubMenu='';

$lang_default=$langDefault;
(!isset($_SESSION["LANG"]))?$_SESSION["LANG"]=$langDefault:$lang=$_SESSION["LANG"];

$indexMenu='';
$indexSubMenu='';
$indexMenuClient='';
$indexSubMenuClient='';
$ar = array();
$nivel=0;

$test = new admin();

$array_temporal =array(); // used for getFullUr

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}
class admin{
    function  __construct()
    {
        $path = PATH_ROOT."/img/";
        if(!is_dir($path)){
            mkdir($path);
        }
        $path = PATH_ROOT."/img/banner/";
        if(!is_dir($path)){
            mkdir($path);
        }
        $path = PATH_ROOT."/img/client/";
        if(!is_dir($path)){
            mkdir($path);
        }
        $path = PATH_ROOT."/img/subasta/";
        if(!is_dir($path)){
            mkdir($path);
        }
        $path = PATH_ROOT."/docs/";
        if(!is_dir($path)){
            mkdir($path);
        }
        $path = PATH_ROOT."/docs/subasta/";
        if(!is_dir($path)){
            mkdir($path);
        }
        $path = PATH_ROOT."/log/";
        if(!is_dir($path)){
            mkdir($path);
        }

    }
    /***************************************************
     function: splitText                     
     @paramIn:  
                $str 
                $n          n?mero de car?cteres
                $delim      default '...'                    
     @paramOut:
                return $str
     description: funci?n para cortar un texto si cortar la
     ?ltima palabra
    ***************************************************/
    public static function splitText($str, $n, $delim='') 
		{
		$len = strlen($str);
		if ($len > $n) 
			{
			$textSplit = substr($str,0,strrpos(substr($str,0,$n)," "));
			return $textSplit.$delim;
			}
		else
			{
			return $str;
			}
		} 
	/***************************************************
     function: translate                     
     @paramIn:  
                $text 
                $type (month, day)                    
     @paramOut:
                return $text
     description: funci?n para la traducir 
     los meses y d?as de ingles a espa?ol
    ***************************************************/
    public static function translate($text, $type='month')
    {
        switch ($type)
        {
            case 'month':
                        switch($text)
                        {
                            case 'January':
                            return 'Enero';
                            break;
                            case 'February':
                            return 'Febrero';
                            break;
                            case 'March':
                            return 'Marzo';
                            break;
                            case 'April':
                            return 'Abril';
                            break;
                            case 'May':
                            return 'Mayo';
                            break;
                            case 'June':
                            return 'Junio';
                            break;
                            case 'July':
                            return 'Julio';
                            break;
                            case 'Agoust':
                            return 'Agosto';
                            break;
                            case 'September':
                            return 'Septiembre';
                            break;
                            case 'October':
                            return 'Octubre';
                            break;
                            case 'November':
                            return 'Noviembre';
                            break;
                            case 'December':
                            return 'Diciembre';
                            break;

                            case '01':
                            return 'Enero';
                            break;
                            case '02':
                            return 'Febrero';
                            break;
                            case '03':
                            return 'Marzo';
                            break;
                            case '04':
                            return 'Abril';
                            break;
                            case '05':
                            return 'Mayo';
                            break;
                            case '06':
                            return 'Junio';
                            break;
                            case '07':
                            return 'Julio';
                            break;
                            case '08':
                            return 'Agosto';
                            break;
                            case '09':
                            return 'Septiembre';
                            break;
                            case '10':
                            return 'Octubre';
                            break;
                            case '11':
                            return 'Noviembre';
                            break;
                            case '12':
                            return 'Diciembre';
                            break;                            
                        }
                
                        break;
            case 'day':
                        switch($text)
                        {
                            case 'Mon':
                            return 'Lun';
                            break;
                            case 'Tue':
                            return 'Mar';
                            break;
                            case 'Wed':
                            return 'Mie';
                            break;
                            case 'Thu':
                            return 'Jue';
                            break;
                            case 'Fri':
                            return 'Vie';
                            break;
                            case 'Sat':
                            return 'Sab';
                            break;
                            case 'Sun':
                            return 'Dom';
                            break;
                        }
                        break;
        }
    }
	
	/***************************************************
     function: SEO                     
      @paramOut:
                return $array       array con el title, metakeyword, metadescription
    ***************************************************/
    public static function SEO($uid)
    {
      global $lang,$domain;
	  $dbLink=new DBmysql;

		$sql = "select col_metatitle,con_parent,col_title   
				from mdl_contents 
				left join mdl_contents_languages on (con_uid=col_con_uid) 
				where con_uid='".$uid."' and col_language='".$lang."' 
				limit 1";
		$dbLink->query($sql);
		$registro = $dbLink->next_record();
		$col_title = $registro["col_title"].' > ';
		$con_parent = $registro["con_parent"];
        if($con_parent){
			return $col_title.admin::SEO($con_parent);
        }
        else{
        	return $col_title;
        }
	}
	
	/*********************************
     function: urlArray                     
     @paramIn:                    
     @paramOut:  
                $urlARRAY___  array con las posiciones del url
     description:   
         funci?n para extraer de la url y separalas por 
         por cada slash
    *********************************/
    public static function urlArray()
    {        
      $aux = substr( $_SERVER['REQUEST_URI'], strlen('/'));
            if( substr( $aux, -1) == '/'){
      $aux=substr( $aux, 0, -1);
      }
      $urlARRAY___ =explode( '/', $aux);
      return $urlARRAY___;
    }
	
	/*********************************
     function: imageName                     
     @paramIn:   
                $name                 
     @paramOut:  
                $nomimag 
     description:   
         funci?n para formatear un texto 
         sin caracteres no validos para
         utilizarlo como nombre de archivo
    *********************************/
    public static function imageName($name)
    	{
        $nomimag = strtolower($name);
        $nomimag = str_replace(" ","_",$nomimag);
        $nomimag = str_replace("?","",$nomimag);
        $nomimag = str_replace("?","a",$nomimag);
        $nomimag = str_replace("?","e",$nomimag);
        $nomimag = str_replace("?","i",$nomimag);
        $nomimag = str_replace("?","o",$nomimag);
        $nomimag = str_replace("?","u",$nomimag);
        $nomimag = str_replace("?","n",$nomimag);
        $nomimag = str_replace("'","",$nomimag);
        $nomimag = str_replace("\"","",$nomimag);
        $nomimag = str_replace("#","",$nomimag);
        $nomimag = str_replace("@","",$nomimag);
        $nomimag = str_replace("!","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);
        $nomimag = str_replace("&","",$nomimag);
        $nomimag = str_replace("/","",$nomimag);
        $nomimag = str_replace("?","_",$nomimag);
        $nomimag = str_replace("?","_",$nomimag);
        $nomimag = str_replace("\\","_",$nomimag);
        $nomimag = str_replace("?","",$nomimag);
        $nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("(","",$nomimag);
        $nomimag = str_replace(")","",$nomimag);
        $nomimag = str_replace(",","",$nomimag);
        $nomimag = str_replace(".","",$nomimag);
        $nomimag = str_replace(";","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("-","",$nomimag);
		$nomimag = str_replace(".","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);	
		$nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("%","",$nomimag);
		$nomimag = str_replace("?","",$nomimag);
		$nomimag = str_replace("[","",$nomimag);
		$nomimag = str_replace("]","",$nomimag);	
		$nomimag = str_replace(":","",$nomimag);	
			
        return $nomimag;
    	}
	/*********************************
     function: changeFormatDate                     
     @paramIn: 
                $date    Fecha
                $type    1(d/m/Y a Y/m/d) 
                         2(Y/m/d a d/m/Y)                 
     @paramOut:  
                $datenew  Nueva Fecha formateada
     description:   
         funci?n para formatear una fecha
    *********************************/
	public static function changeFormatDate($date,$type)
	{
	switch ($type)
		{
		// de d/m/Y a Y-m-d
		case 1: $dateant = explode("/",$date);
				$datenew = $dateant[2] . "-" . $dateant[1] . "-" . $dateant[0];
				break;
		// de Y-m-d a d/m/Y
		case 2: 
				$dateant = explode("-",$date);
				$datenew = $dateant[2] . "/" . $dateant[1] . "/" . $dateant[0];
				break;	
		case 3:
		// de dmY a Y-m-d		
				$day = substr($date,0,2);
				$month = substr($date,2,2);
				$year = substr($date,4,2);
				$datenew = $year . "-" . $month . "-" . $day;
				break;
		case 4:
		// de dmY a d-m-Y		
				$day = substr($date,0,2);
				$month = substr($date,2,2);
				$year = substr($date,4,2);
				$datenew = $day . "/" . $month . "/" . $year;
				break;				
		case 5:
		// de Y-m-d a d de Mes de Y		
				$dateant = explode("-",$date);
				$monthName = admin::getMonthName($dateant[1],'es');
				$datenew = $dateant[2] . " de " . $monthName . " de " . $dateant[0];
				break;	
		case 6:
		// de Y-m-d-w a "Dia literal, d de mes literal"
				$dateant = explode("-",$date);
				$monthName = admin::getMonthName($dateant[1],'es');
				$dayName = admin::getDayName($dateant[3],'es');
				$datenew = $dayName . ", " . $dateant[2] . " de " . $monthName . " de " . $dateant[0];
				break;	
		case 7:
		// de Y-m-d H:i:s a d-m-Y H:i:s
				$year = substr($date,0,4);
				$month = substr($date,5,2);
				$day = substr($date,8,2);
				$time = substr($date,10);
				$datenew = $day. "-" . $month . "-" . $year.$time;
				break;	
		}
	return $datenew;
	}
	
    /*********************************
     function: menuDynamic                     
     @paramIn: 
                $a      uid del padre
                $j      nivel inicial 0
                $k      valor inicial 0
     @paramOut:  
                $ar     $array con los datos del 
                        ?rbol
     description:   
         funci?n recursiva para sacar todos los elementos
         dependientes de un contenido padre
    *********************************/    
   public static function menuDynamic($a,$j,$k)
        { 
          global $db;
          global $ar;
          if ($a!=null)
          {
             $dat = "SELECT con_uid FROM mdl_contents where con_parent=".$a." order by con_position";
             $db->query($dat); 
             $k++;
              while($next_record = $db->next_record())
                {
                 $j++;
                 $ar[$j]=$next_record["con_uid"];
                }
            admin::arbol($ar[$k],$j,$k);
          }
          return($ar);
        }
	/*********************************
	function: urlsFriendly                     
	@paramIn: 
	$url      
	@paramOut:  
	$url   
	description:   
	funci?n para eliminar car?cteres
	no validos para el uso de rutas 
	amigables
	*********************************/        
	public static function urlsFriendly($url) { 

		$find = array('?', '?', '?', '?', '?', '?','?','?','?','?','?','?'); 
		$repl = array('a', 'e', 'i', 'o', 'u', 'n','A','E','I','O','U','N'); 
		$url = str_replace ($find, $repl, $url); 
		$url = strtolower($url);
		$find = array(' ', '&', '\r\n', '\n', '+'); 
		$url = str_replace ($find, '-', $url); 
		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/'); 
		$repl = array('', '-', ''); 
		$url = preg_replace ($find, $repl, $url); 
		return $url; 
		}
	/*********************************
	function: initializeClient                     
	@paramIn: 
			$sMenu
			$sSubMenu      
	@paramOut:  
			   
	description:   
	 funci?n para inicializar la session 
	 para el lado cliente
	*********************************/        
	public static function initializeClient(){
		global $domain, $tiempoMax;
		$uidClient= admin::getSession("uidClient");
    $tiempoActual = time();
    $tiempoNuevo = $tiempoActual + (60*$tiempoMax);
    $validaSession =  admin::getDBValue("select count(*) from sys_session where (ses_user_uid=" .admin::toSql($uidClient,"Integer"). ") and ses_lastactivity>$tiempoActual and ses_registered='V'");
    if($validaSession==0)  { header("HTTP/1.0 403 Forbidden");header("Location: ".$domain."/logout.php");}
    else
    {
      $rs = new DBmysql();
      $rs->query("update sys_session set ses_lastactivity=$tiempoNuevo where (ses_user_uid=" .admin::toSql($uidClient,"Integer"). ")");
    }

		if(!$uidClient) header("Location: ".$domain."/1/");
		}
	/*********************************
	function: initialize                     
	@paramIn: 
	$sMenu
	$sSubMenu      
	@paramOut:  
	
	description:   
	funci?n para inicializar la session 
	para admin
	*********************************/        

		
	public static function initialize($sMenu, $sSubMenu,$redirect=true){
	
		//@session_start();
//		Verifying token
		global $basedatos, $host, $user,$pass,$domain;
                self::checkIP();
                $rs = new DBmysql();
                $token =  SymmetricCrypt::Encrypt($_SESSION["usr_uid"].$_SESSION["usr_rol"].date("Ymd")."JS");
		$sql = "select suv_status from sys_users_verify where suv_cli_uid='".$_SESSION["usr_uid"]."' and suv_token='".$token."' and suv_ip='".$_SERVER['REMOTE_ADDR']."'";
		//$sql = "select suv_status from sys_users_verify where suv_cli_uid='".$_SESSION["usr_uid"]."' and suv_ip='".$_SERVER['REMOTE_ADDR']."' and suv_status=0";
		//echo($sql);//die;
		$rs->query($sql);
                //print_r($rs);die;
        if ($row = $rs->next_record()){
        	if ( $row["suv_status"] === "0") {
        		/*begin autenticated */
					$sMenu=admin::getDbValue("select mod_uid from sys_modules where mod_alias='".$sMenu."'");
					$sSubMenu=admin::getDbValue("select mod_uid from sys_modules where mod_alias='".$sSubMenu."'");
					global $indexMenu, $indexSubMenu;
					$indexMenu = $sMenu;
					$indexSubMenu = $sSubMenu;
			
						if ($sMenu!="login" && $sSubMenu!="login") 
						{
						if (!$_SESSION["usr_uid"])
							{
								unset($SESSION["usr_uid"]) ;
								if($redirect){
									//die('1');
                                                                        header("HTTP/1.0 403 Forbidden");exit;
									header('Location: '.PATH_DOMAIN.'/logout.php');
									}
					        	else{ header("HTTP/1.0 403 Forbidden");exit;
                                                        header('Location: '.PATH_DOMAIN.'/logout.php');
                                                        
                                                        }//die('No tiene permisos');
                                                        }else{
                                                            
                                                            ///ACA DEBEMOS VER Q SE HACE PARA LOS Q NO TIENEN PERMISO
                                                          /*  if(!self::verifyModulePermission($sMenu))
                                                            {
                                                                $modAccess = admin::getDBvalue("select top 1 a.mus_mod_uid from sys_modules_users a, sys_modules b where a.mus_rol_uid=".$_SESSION["usr_rol"]." and a.mus_mod_uid=b.mod_uid and b.mod_status='ACTIVE' and b.mod_parent=0 order by b.mod_position");
                                                                $urlSite = admin::getDBValue("select mod_index from sys_modules where mod_uid=". $modAccess ." and mod_status='ACTIVE'");
                                                                if($urlSite){
                                                                    
                                                                //header("Location: ".PATH_DOMAIN."/".$urlSite);
                                                                }//else header("Location: ".PATH_DOMAIN."/index.php");
                                                            }*/
                                                        }
						}
        		/*end autenticated */	
        	}
	        else {
	        		if($redirect){
	        			//die('2');
                                                header("HTTP/1.0 403 Forbidden");exit;
						header('Location: '.PATH_DOMAIN.'/logout.php');
						}
		        	else {header("HTTP/1.0 403 Forbidden");exit;
		        		header('Location: '.PATH_DOMAIN.'/logout.php');//die('No tiene permisos');
                                        }
	        }
        }
        else {
        	if($redirect){
        		//die('3');
				header("HTTP/1.0 403 Forbidden");exit;header('Location: '.PATH_DOMAIN.'/logout.php');
				}
        	else {
                    header("HTTP/1.0 403 Forbidden");exit;
        		header('Location: '.PATH_DOMAIN.'/logout.php');//die('No tiene permisos');
                }
        }
	
	 }
  
		
	/*********************************
     function: tips                     
     @paramIn: 
                $sID
                $sOP (label)      
     @paramOut:  
                $labels[$sID][$sOP]    
     description:   
         funci?n para los textos de ayuda
         para admin
    *********************************/        


    public static function tips($sID,$sOP='label')
        { 
        global $lang, $lang_default;  
        global $labels,$linksLabels;
        global $indexMenu, $indexSubMenu;
        
        if ($indexSubMenu !='') 
            {
            if ($lang=='') $lang=$lang_default;  
            if (file_exists("label/$lang/labels_tips.php"))
                {
                require_once("label/$lang/labels_tips.php");
                }
            if (is_array($systemTips))
                {   
                $labels = array_merge($systemTips,$labels);
                }
            }            
        return ($labels[$sID][$sOP] != '' ? $labels[$sID][$sOP] : '-');            
        } 
	/*********************************
     function: labels                     
     @paramIn: 
                $sID
                $sOP (label)      
     @paramOut:  
                $labels[$sID][$sOP]    
     description:   
         funci?n para las etiquetas 
         para admin
    *********************************/   
	public static function labels($sID,$sOP='label')
		{ 
		global $lang, $lang_default;  
		global $labels,$linksLabels;
		global $indexMenu, $indexSubMenu;
			if ($lang=='') $lang=$lang_default;
			
			$lab_label = admin::getDbValue("select lab_label from sys_labels where lab_uid='".$sID."' and lab_category='".$sOP."' and lab_language='".$lang."' and lab_status='ACTIVE' and lab_delete=0");	
			return $lab_label;

		}
		
	/*********************************
     function: labelsExecute                     
     @paramIn:   
                $sID,$sOP                
     @paramOut:  
                Label en el leguage deseado
     description:   
         Permite obtener un label pero si estamos en el directorio execute ejecutando un c?digo
    *********************************/		   
	public static function labelsExecute($sID,$sOP='label')
		{

		global $lang, $lang_default;  
		global $labels,$linksLabels;
		global $indexMenu, $indexSubMenu;
		//if ($lang=="") $lang="es";
		if ($indexSubMenu !='') 
			{
			if ($lang=='') $lang=$lang_default;  
			if (file_exists("../../label/$lang/labels.php"))
				{
				require_once("../../label/$lang/labels.php");
				}
			if (is_array($systemLabels))
				{   
				$labels = array_merge($systemLabels,$labels);
				}
			}			
		return ($labels[$sID][$sOP] != '' ? $labels[$sID][$sOP] : '-');			
		}    


	/*********************************
	function: labelsMenu                     
	@paramIn:                       
	@paramOut:  
	$labelsMenu   
	description:   
	funci?n para las etiquetas para el 
	Menu principal para admin
	*********************************/                
	public static function labelsMenu(){ 
		global $lang, $lang_default;  
		global $indexMenu;
		if ($lang=='') $lang=$lang_default;  
		
		//print_r($systemLabelsMenu);
		      $rs = new DBmysql();
        //$rs->connect("$basedatos", "$host", "$user", "$pass");
	//echo "INDEX: ".$indexMenu."##";	
        //$rs = new DBmysql($basedatos, $host, $user,$pass);
        $sqldat = "select * from sys_modules where mod_status='ACTIVE' and mod_parent=0 and mod_language='".$lang."' order by mod_position";
        $rs->query($sqldat);
        while ($row = $rs->next_record()){
          //print_r($row);
				if (admin::verifyModulePermission($row['mod_uid']))
					{
					 //echo "....".$row['mod_uid']."-";die;
                                         $mod_uid = admin::getDbValue("select mod_uid from sys_modules where mod_parent=".$row['mod_uid']." and mod_index='".$row['mod_index']."'");
                                         $urlModule =$row['mod_index'];
                                               if (strlen(strstr($urlModule,"?"))>0) {
                                                    $urlModule=$urlModule."&";
                                                }else{
                                                    $urlModule=$urlModule."?";
                                                }
					 if ($row['mod_uid']==$indexMenu) {
					  	$labelsMenu .= '<li><a id="first" title="'.$row['mod_name'].'" href="'.$urlModule.'mod_uid='.$mod_uid.'">'.$row['mod_name'].'</a></li>'; 
                                         }
                                         else
                                         {
					  	$labelsMenu .= '<li><a title="'.$row['mod_name'].'" href="'.$urlModule.'mod_uid='.$mod_uid.'">'.$row['mod_name'].'</a></li>';						
                                         }
					}
        }

		return $labelsMenu;
		}
    
    /*********************************
     function: labelsSubMenu                     
     @paramIn: 
                      
     @paramOut:  
                $labelsSubMenu   
     description:   
         funci?n para las etiquetas para el 
         SubMenu principal para admin
    *********************************/                
	 
	public static function labelsSubMenu()
        { 
		global $lang, $lang_default;  
		global $labelsSubMenu;
		global $indexMenu, $indexSubMenu;
		if ($lang=='') $lang=$lang_default;
          $rs = new DBmysql();
          if($indexMenu){
	        $sqldat = "select mod_uid, mod_name, mod_index from sys_modules, sys_modules_users where mus_mod_uid=mod_uid and mod_status='ACTIVE' and mod_parent=".$indexMenu." and mod_language='".$lang."' and mus_rol_uid='".$_SESSION["usr_rol"]."' and mus_delete=0 and mus_place='MODULE' group by mod_uid, mod_name, mod_index order by mod_uid";
                //echo $sqldat;die;
	        $rs->query($sqldat);
	        while ($row = $rs->next_record()){
					$params = (!empty(self::getParam("con_parent")) ? "con_parent=".admin::toSql(safeHtml(self::getParam("con_parent")),"Number")."&mod_uid=".$row['mod_uid'] : "mod_uid=".$row['mod_uid']);
                                        $urlSubModule =$row['mod_index'];
                                                if(strpos($urlSubModule, '?')!==FALSE){
                                                    $urlSubModule=$urlSubModule."&";
                                                }else{
                                                    $urlSubModule=$urlSubModule."?";
                                                }
						if ($row['mod_uid']==$indexSubMenu) $labelsSubMenu .= $row['mod_name'];
						else $labelsSubMenu .= "<a title=\"".$row['mod_name']."\" href=\"".$urlSubModule.$params."\" class=\"submenu\">".$row['mod_name']."</a>";
                        }
                    }

		return $labelsSubMenu;
		}

    /*********************************
     function: labelsSystem                     
     @paramIn: 
                $sID
                $sOP    ('label')      
     @paramOut:  
                $labelsSubMenu   
     description:   
         funci?n para las etiquetas para el 
         sistema principal para admin
    *********************************/  		
	public static function labelsSystem($sID,$sOP='label')
		{ 
		global $lang, $lang_default;  
		global $labels;
		if ($lang=='') $lang=$lang_default;  
		
		$lab_label = admin::getDbValue("select lab_label from sys_labels where lab_uid='".$sID."' and lab_category='".$sOP."' and lab_language='".$lang."' and lab_status='ACTIVE' and lab_delete=0");
		return $lab_label;

		}
	/*********************************
     function: labelsFront                     
     @paramIn: 
                $sID
                $sOP    ('main')      
     @paramOut:  
                $labelsSubMenu   
     description:   
         funcin para las etiquetas para el Front         
    *********************************/  		
	public static function labelsFront($sID,$sOP='main')
		{ 
		global $lang, $lang_default;  
		global $labels;
		if ($lang=='') $lang=$lang_default;  
		
		$lab_label = admin::getDbValue("select lab_label from tbl_labels where lab_uid='".$sID."' and lab_category='".$sOP."' and lab_language='".$lang."' and lab_status='ACTIVE' and lab_delete=0");
		return $lab_label;
		}
		

/*********************************
     function: toHtml                     
     @paramIn: 
                $strValue
     @paramOut:  
                $strValue   
     description:   
         funci?n para las convertir car?cteres
         especiales de html
    *********************************/         
	public static function toHtml($strValue)
		{
		return htmlspecialchars($strValue);
		}
    /*********************************
     function: toUrl                     
     @paramIn: 
                $strValue
     @paramOut:  
                $strValue   
     description:   
         funci?n para las convertir car?cteres
         especiales v?lidos para url
    *********************************/         
	public static function toUrl($strValue)
		{
		return urlencode($strValue);
		}
     /*********************************
     function: getParam                     
     @paramIn: 
                $param_name
     @paramOut:  
                $param_value   
     description:   
         funci?n para las capturar variables enviadas
         sin importar el m?todo de transferencia,
         ni la versi?n de PHP que se utiliza
    *********************************/       
	public static function getParam($param_name,$type="")
		{
		global $HTTP_POST_VARS;
		global $HTTP_GET_VARS;
		global $_POST;
		global $_GET;
		$param_value = "";
		if(isset($HTTP_POST_VARS[$param_name]))
		$param_value = $HTTP_POST_VARS[$param_name];
		else if(isset($HTTP_GET_VARS[$param_name]))
		$param_value = $HTTP_GET_VARS[$param_name];
		else if(isset($_POST[$param_name]))
		$param_value = $_POST[$param_name];
		else if(isset($_GET[$param_name]))
		$param_value = $_GET[$param_name];
                if($type=="strip")
		return self::strip($param_value);
                else
		return self::doFilter($param_value);
		}
    /*********************************
     function: getSession                     
     @paramIn: 
                $param_name
     @paramOut:  
                $param_value   
     description:   
         funci?n para las obtener variables de session,
         independientes del array $_SESSION, 
         sin importar la versi?n de PHP que se utiliza
    *********************************/              
    public static function getSession($param_name)
		{
		global $_SESSION;
		$param_value = "";
		if(isset($_SESSION[$param_name])) $param_value = $_SESSION[$param_name];
		//$param_value = rtrim(SymmetricCrypt::Decrypt($_SESSION[$param_name]));
		else unset($param_value);//="";
		return $param_value;
		
		}
    /*********************************
     function: getCookie                     
     @paramIn: 
                $param_name
     @paramOut:  
                $param_value   
     description:   
         funci?n para las obtener un cookie especifico,
         sin importar la versi?n de PHP que se utiliza
    *********************************/                      
        
    public static function getCookie($param_name)
        {
        global $HTTP_COOKIE_VARS;
        global $_COOKIE;
        global ${$param_name};
        $param_value = "";
        if(isset($_COOKIE[$param_name]))
        $param_value = ${$param_name};
        if(isset($HTTP_COOKIE_VARS[$param_name]))
        $param_value = ${$param_name}; 
        return $param_value;
        } 
    /*********************************
     function: setSession                     
     @paramIn: 
                $param_name
     @paramOut:  
                   
     description:   
         funci?n para las fijar variables de session,
         independientes del array $_SESSION, 
         sin importar la versi?n de PHP que se utiliza
    *********************************/              
public static function setSession($param_name, $param_value)
		{
		global $_SESSION;
		if(isset($_SESSION[$param_name]))
		$_SESSION[$param_name]='';
		$param_value=$param_value;
		//$param_value=SymmetricCrypt::Encrypt($param_value);
	    $_SESSION[$param_name] = $param_value;
		return true;
		}
    /*********************************
     function: isNumber                     
     @paramIn: 
                $string_value
     @paramOut:  
                true|false
     description:   
         funci?n para validar si el valor es 
         n?merico
    *********************************/          
	public static function isNumber($string_value)
		{
		if(is_numeric($string_value) || !strlen($string_value))
		return true;
		else
		return false;
		}
   /*********************************
     function: toSql                     
     @paramIn: 
                $value
     @paramOut:  
                $type   "Number","Text"
     description:   
         funci?n para evaluar una valor 
         para ser introducido en una sentencia
         SQL en el caso de ser n?merico convierte
         en formato de la base de Datos, en caso
         de ser erroneo y en el caso de ser
         un texto comenta los caracteres no validos
         ' \ evitando que se realice un SQLInjection
    *********************************/          
	public static function toSql($value, $type="")
		{
		if(!strlen($value))
			if($type == "Number") return 0;
			else return "";
		else
			if($type == "Number")
			{
				$value = str_replace (",", ".", doubleval($value));
				if (is_numeric($value)) return $value; else return 0;
			}
			else
				{
                                    if($type=="strip")
                                        return self::doFilter($value,"sql");
                                    else 
                                        return self::strip($value);
				}
		}
        /*********************************
     function: doFilter                
     @paramIn: 
                $value
     @paramOut:  
                $value   
     description:   
         funci?n para eliminar valores introducidos 
         y no validos en base de datos ejemplo /.    
         * 
         **********************************/    
        public static function doFilter($value, $mode="html") {
        switch ($mode) {
            case 'html':
                $value = stripslashes($value);
                $value = strip_tags($value);
                $value = addslashes($value);
                $value = htmlspecialchars($value);
                break;
            case 'sql':
                $value = stripslashes($value);
                $value = preg_replace(sql_regcase('/(from|select|insert|delete|where|drop table|show tables|#|\*| |\\\\)/'),'',$value);
                $value = trim($value);                
                break;
        }
        return $value;
    }
    /*********************************
     function: strip                
     @paramIn: 
                $value
     @paramOut:  
                $value   
     description:   
         funci?n para eliminar valores introducidos 
         y no validos en base de datos ejemplo /.
    ***********************************/         
  public static function strip($value)
	{
              /*  $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
                $value = filter_var($value, FILTER_SANITIZE_STRIPPED);
                
                */
	if(get_magic_quotes_gpc() == 0)
		return $value;
	else
	return stripslashes($value);
	}  
    /*********************************
     function: dbFillArray                
     @paramIn: 
                $sql_query
     @paramOut:  
                $ar_lookup   
     description:   
         funci?n que ejecuta un query con una devoluci?n a un array 
         de dimensi?n 2xN ?til en el caso de querer extraer
         un uid y su descripci?n de alguna tabla
    ***********************************/                 
    public static function dbFillArray($sql_query)
    {
          global $basedatos, $host, $user,$pass;
          $db_fill = new DBmysql();
          //$db_fill->connect("$basedatos", "$host", "$user", "$pass");
          $db_fill->query($sql_query);
          while($Array=$db_fill->next_record())
          {
              $ar_lookup[$Array[0]] = $Array[1];
          }
          return $ar_lookup;
     }
    /*********************************
     function: dLookUp                
     @paramIn: 
                $table_name
                $field_name
                $where_condition
     @paramOut:  
                $record[0]   
     description:   
         funci?n que ejecuta un query con una devoluci?n a un 
         dato especifico
    ***********************************/                 
    public static function dLookUp($table_name, $field_name, $where_condition)
    {
      $sql = "SELECT " . $field_name . " FROM " . $table_name . " WHERE " . $where_condition;
      return admin::getDbValue($sql);
    }
    /*********************************
     function: getDbValue                
     @paramIn: 
                $sql
     @paramOut:  
                $record[0]   
     description:   
         funci?n que ejecuta un query con la 
         devoluci?n de un dato especifico
    ***********************************/                 
    public static function getDbValue($sql)
    {
      $db_look = new DBmysql();
      $db_look->query($sql);
      if($record = $db_look->next_record())
        return $record[0];
      else
        return "";
    }
/*********************************
     function: getCheckboxValue                
     @paramIn: 
                $value
                $checked_value
                $unchecked_value
                $type
     @paramOut:  
                tosql($checked_value, $type)   
     description:   
         funci?n que evalua si un checkbos se 
         encuentra tiqueado y lo convierte en el
         valor para ser ingresado a una instrucci?n
         sql seg?n los valores que se requieran y definiendo
         el tipo de dato que corresponde.
    ***********************************/     
    public static function getCheckboxValue($value, $checked_value, $unchecked_value, $type)
    {
      if(!strlen($value))
        return tosql($unchecked_value, $type);
      else
        return tosql($checked_value, $type);
    }
    /*********************************
     function: getLovValue                
     @paramIn: 
                $value
                $array
     @paramOut:  
                $return_result
     description:   
         funci?n que obtiene un  valor que se 
         encuentra en un array, buscando por 
         su llave (key), retornando su valor (value)
    ***********************************/     
    public static function getLovValue($value, $array)
    {
      $return_result = "";
      if(sizeof($array) % 2 != 0)
        $array_length = sizeof($array) - 1;
      else
        $array_length = sizeof($array);
      reset($array);
      for($i = 0; $i < $array_length; $i = $i + 2)
      {
        if($value == $array[$i]) $return_result = $array[$i+1];
      }
      return $return_result;
    }	
/*********************************
     function: getLinkUid                     
     @paramIn:   
                $uid,$title
     @paramOut:  
               $url
     description:   
         Funcion recursiva que devuelve el url de los contenidos para poder acceder al mismo mediante rutas amigables
    *********************************/	
public static function getLinkUid($uid,$title)
	{
	$sql = "select * 
			from mdl_contents left join mdl_contents_languages on (con_uid=col_con_uid) 
			where col_language='" . SYS_LANG . "' and 
				  con_parent=" . $uid . " and 
				  col_status='ACTIVE' and
				  con_delete<>1  
			order by con_position";
	$dbLink=new DBmysql;
	//$dbLink->connect("$basedatos", "$host", "$user", "$pass");		
	$dbLink->query($sql);		
	$numsubs = $dbLink->numrows();
	
	if ($numsubs>0)
		{
		$nextuid = $dbLink->next_record();
		return admin::getLinkUid($nextuid["con_uid"],$nextuid["col_url"]);
		}
	else
		{
		return $title;
		}
	}

// OBTIENE EL NOMBRE DEL CONTENIDO DEL MENU
public static function getContentTitle($uid)
	{

	$contentTitle = "";
	if ($uid!="")
		{
		$firstuid = $uid;
		$sql = "select * 
				from mdl_contents 
				left join mdl_contents_languages on (con_uid=col_con_uid) 
				where con_uid='" . $firstuid . "' and col_language='" . SYS_LANG . "'";
//		echo $sql;die;
		$dbLink=new DBmysql;
		//$dbLink->connect("$basedatos", "$host", "$user", "$pass");		
		$dbLink->query($sql);		
		$registro = $dbLink->next_record();
		$contentTitle = $registro["col_title"];
		}
	return $contentTitle;
	}

public static function getContentUrl($uid)
	{
		global $lang;
	$contentTitle = "";
	if ($uid!="")
		{
		$firstuid = $uid;
		$sql = "select * 
				from mdl_contents 
				left join mdl_contents_languages on (con_uid=col_con_uid) 
				where con_uid='" . $firstuid . "' and col_language='" . $lang . "'";
		//echo $sql;die;
		$dbLink=new DBmysql;
		//$dbLink->connect("$basedatos", "$host", "$user", "$pass");		
		$dbLink->query($sql);		
		$registro = $dbLink->next_record();
		$contentTitle = $registro["col_url"];
		}
	return $contentTitle;
	}	
	
/**
 * Gets complete url
 *
 * @param integer $uid
 * @return string
 */
public static function getFullUrl($uid,$resetArray=true){
	global $lang,$domain,$array_temporal;
	if($resetArray) $array_temporal=array();
	$dbLink=new DBmysql;

		$sql = "select col_url,con_parent  
				from mdl_contents 
				left join mdl_contents_languages on (con_uid=col_con_uid) 
				where con_uid='" . $uid . "' and col_language='" . $lang . "' 
				limit 1";
		$dbLink->query($sql);
		$registro = $dbLink->next_record();
		$col_url = $registro["col_url"];
		$con_parent = $registro["con_parent"];
        if($con_parent){
        	if($registro["con_uid"]!=1)
        	array_unshift($array_temporal,$col_url);
        	admin::getFullUrl($con_parent,false);
        	return ($domain.'/'.($lang==SYS_LANG ? '':$lang.'/').implode($array_temporal,"/").'/');
        }
        else{
        	array_unshift($array_temporal,$col_url);
        	return $col_url;
        }
        
}

public static function getContentDescription($uid)
	{

	$contentTitle = "";
	if ($uid!="")
		{
		$firstuid = $uid;
		$sql = "select col_content  
				from mdl_contents 
				left join mdl_contents_languages on (con_uid=col_con_uid) 
				where con_uid='" . $firstuid . "' and col_language='" . SYS_LANG . "'";
		$dbLink=new DBmysql;
		//$dbLink->connect("$basedatos", "$host", "$user", "$pass");		
		$dbLink->query($sql);		
		$registro = $dbLink->next_record();
		$contentTitle = $registro["col_content"];
		}
	return $contentTitle;
	}		
public static function getSupId($link_uid)
	{
	$sql = "select * 
			from mdl_contents left join mdl_contents_languages on (con_uid=col_con_uid) 
			where col_language='" . SYS_LANG . "' and 
			con_uid='" . $link_uid."'";

	$dbLink=new DBmysql;
	//$dbLink->connect("$basedatos", "$host", "$user", "$pass");		
	$dbLink->query($sql);		
	$numsubs = $dbLink->numrows();	
	$nextuid = $dbLink->next_record();
	if ($nextuid["con_parent"]==0) return $link_uid;
	else return $nextuid["con_parent"];
	}
	
public static function getSiteName($uid)
	{
	$sql = "select * from mdl_sites WHERE sit_uid=".$uid;
	$db=new DBmysql;
	//$db->connect("$basedatos", "$host", "$user", "$pass");		
	$db->query($sql);		
	$numsite = $db->numrows();	
	$sitename = $db->next_record();
	if ($sitename["sit_name"]=="") return $uid;
	else return $sitename["sit_name"];
	}

public static function getExtension($file){
		return pathinfo($file,PATHINFO_EXTENSION);
	}
public static function getImageName($fullname){
	$info =  pathinfo($fullname);
	return	admin::imageName($info['filename']);
}	
	
public static function getExtensionImage($ext)
	{
	$imageIcon = "";

	switch(strtoupper($ext))
		{
		case "PDF": $imageIcon="pdf.gif"; break;
		case "GIF": $imageIcon="image.gif"; break;
		case "JPEG": $imageIcon="image.gif"; break;
		case "JPG": $imageIcon="image.gif"; break;
		case "PNG": $imageIcon="image.gif"; break;
		case "BMP": $imageIcon="image.gif"; break;
		case "DOC": $imageIcon="word.gif"; break;
		case "XLS": $imageIcon="excel.gif"; break;
		case "PNG": $imageIcon="image.gif"; break;
		case "FLA": $imageIcon="flash.png"; break;
		case "HTML": $imageIcon="link.png"; break;
		case "HTM": $imageIcon="link.png"; break;
		case "TXT": $imageIcon="texto.gif"; break;
		
		default : $imageIcon="none.png";
		}
	if ($imageIcon!="") $imageIcon= "lib/ext/" . $imageIcon;
	return $imageIcon;
	}

	public static function getDayName($day,$language)
		{
		//echo "<br>-->" . $month;die;
		switch($day)
			{
			case "0" : $dayName = "Domingo";break;
			case "1" : $dayName = "Lunes";break;
			case "2" : $dayName = "Martes";break;
			case "3" : $dayName = "Mi&eacute;rcoles";break;
			case "4" : $dayName = "Jueves";break;
			case "5" : $dayName = "Viernes";break;
			case "6" : $dayName = "S&aacute;bado";break;
			}
		return $dayName;				
		}
	public static function getMonthName($month,$language)
	{
	//echo "<br>-->" . $month;die;
	switch($month)
		{
		case "01" : $monthName = "enero";break;
		case "02" : $monthName = "febrero";break;
		case "03" : $monthName = "marzo";break;
		case "04" : $monthName = "abril";break;
		case "05" : $monthName = "mayo";break;
		case "06" : $monthName = "junio";break;
		case "07" : $monthName = "julio";break;
		case "08" : $monthName = "agosto";break;
		case "09" : $monthName = "septiembre";break;
		case "10" : $monthName = "octubre";break;
		case "11" : $monthName = "noviembre";break;
		case "12" : $monthName = "diciembre";break;
		}
	return $monthName;		
	}
	public static function getTemplateId($uid)
		{
	$sql = "select * 
			from mdl_bulletin  
			WHERE bul_uid=" . $uid;
	$db=new DBmysql;
	//$db->connect("$basedatos", "$host", "$user", "$pass");		
	$db->query($sql);		
	//$numsite = $db->numrows();	
	$template = $db->next_record();	
	if ($template["bul_but_uid"]=="") $tmp_name = "";
	else $tmp_name = $template["bul_but_uid"];	
	return $tmp_name;
		}	
public static function getDocsCategoryTitle($dca_uid)
	{
	$sql = "select * from mdl_docs_category where dca_uid=" . $dca_uid;
	global $basedatos, $host, $user,$pass ;
	$db_category = new DBmysql();
	//$db_category->connect("$basedatos", "$host", "$user", "$pass");
	$db_category->query($sql);
	if($record = $db_category->next_record())
	return $record["dca_category"];
	else
	return "";
	}
public static function getDocsCategoryUrl($dca_uid)
	{
	$sql = "select * from mdl_docs_category 
			left join mdl_docs_category_languages on (dca_uid=dcl_dca_uid) 			
			where dca_uid=" . $dca_uid . " and dcl_language='es' 
			limit 1";

	global $basedatos, $host, $user,$pass ;
	$db_category = new DBmysql();
	//$db_category->connect("$basedatos", "$host", "$user", "$pass");
	$db_category->query($sql);
	if($record = $db_category->next_record())
	return $record["dcl_url"];
	else
	return "";
	}
public static function getMultCategoryTitle($dca_uid)
	{
	$sql = "select * from mdl_mult_category where muc_uid=" . $dca_uid;
	global $basedatos, $host, $user,$pass ;
	$db_category = new DBmysql();
	//$db_category->connect("$basedatos", "$host", "$user", "$pass");
	$db_category->query($sql);
	if($record = $db_category->next_record())
	return $record["muc_category"];
	else
	return "";
	}
public static function getMultCategoryUrl($dca_uid)
	{
	$sql = "select * from mdl_mult_category where muc_uid=" . $dca_uid;
	global $basedatos, $host, $user,$pass ;
	$db_category = new DBmysql();
	//$db_category->connect("$basedatos", "$host", "$user", "$pass");
	$db_category->query($sql);
	if($record = $db_category->next_record())
	return $record["muc_url"];
	else
	return "";
	}
/*********************************
	ELIMINAR
     function: resize                
     @paramIn: 
                $path
                $saveTo  (null)
                $width2
                $height2
     @paramOut:  
                $saveTo
     description:   
         funci?n que realiza un resize de tama?o de una
         im?gen, sin deformarla, formatos permitidos (jpg,png,gif)
    ***********************************/     
    public static function resize($path, $saveTo=NULL, $ancho, $calidad)
    {
		//echo  $ancho . $calidad."!";
       if(file_exists($path))
       {
        list($width, $height) = getimagesize($path);
        /*
        $percentAlto   = $height2 / $height;
        $percentAncho = $width2 / $width;
        $percent = $percentAncho;
        $width2 = $width * $percent;
        $height2 = $height * $percent;
		 //$height2 = $height;*/
       $ancho = $ancho; //$t_ancho * $alto / $ancho	//$t_ancho * $alto / $ancho;
	   $alto = ($ancho * $height)/$width;	
	
	 
        // Resample
        $image_p = imagecreatetruecolor($ancho, $alto);
        if (strcasecmp(substr($path,-4),'.jpg')===0)$image = imagecreatefromjpeg($path);
        if (strcasecmp(substr($path,-5),'.jpeg')===0)$image = imagecreatefromjpeg($path);
        if (strcasecmp(substr($path,-4),'.png')===0)$image = imagecreatefrompng($path);
        if (strcasecmp(substr($path,-4),'.gif')===0)$image = imagecreatefromgif($path);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $ancho, $alto, $width, $height);
        // Output
        //if (isset($saveTo)) Header("Content-type: image/jpeg");
        imagejpeg($image_p, $saveTo, $calidad);
        return $saveTo;
       }
       else return "error file not found";
       
    } 
/*********************************
	ELIMINAR
     function: resize                
     @paramIn: 
                $path
                $saveTo  (null)
                $width2
                $height2
     @paramOut:  
                $saveTo
     description:   
         funcin que realiza un resize de tamao de una
         imgen, sin deformarla, formatos permitidos (jpg,png,gif)
    ***********************************/     
    public static function resize2($path, $saveTo=NULL, $height2)
    {
       // echo $saveTo;  die;
        list($width, $height) = getimagesize($path);
        
        $percent   = $height2 / $height;
        $width2 = $width * $percent;
        $height2 = $height * $percent;
        
        // Resample
        $image_p = imagecreatetruecolor($width2, $height2);
        if (strcasecmp(substr($path,-4),'.jpg')===0)$image = imagecreatefromjpeg($path);
        if (strcasecmp(substr($path,-5),'.jpeg')===0)$image = imagecreatefromjpeg($path);
        if (strcasecmp(substr($path,-4),'.png')===0)$image = imagecreatefrompng($path);
        if (strcasecmp(substr($path,-4),'.gif')===0)$image = imagecreatefromgif($path);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width2, $height2, $width, $height);
        // Output
        //if (isset($saveTo)) Header("Content-type: image/jpeg");
        imagejpeg($image_p, $saveTo, 100);
        return $saveTo;
    }  	  
/*********************************
	ELIMINAR
     function: verfyModulePermission                
     @paramIn: 
                $id
     @paramOut:  
                true || false
     description:   
         Devuelve verdadero si el usuario est? habilitado para usar el modulo
    ***********************************/     
public static function verifyModulePermission($id){
	/* AUMENTADO PARA EL CONTROL DE PERMISOS POR USUARIO */
	$sql = "select * 
			from sys_modules_users 
			left join sys_modules on (mus_mod_uid=mod_uid) 
			where mus_rol_uid='".$_SESSION["usr_rol"]."' and mus_place='MODULE' and mod_uid='".$id."' and mus_delete=0";

      
	$dbLink=new DBmysql;
	$dbLink->query($sql);
	$nroreg = $dbLink->numrows();
  //echo "MOD:".$id." Reg:".$nroreg;
	if ($nroreg!=0)
		return true;
	else
		return false;
	/* AUMENTADO PARA EL CONTROL DE PERMISOS POR USUARIO */	
	}    
/*********************************
	ELIMINAR
     function: getFirstModule                
     @paramIn: 
                $id
     @paramOut:  
                true || false
     description:   
         Devuelve el primer modulo al cual puede ingresar el usuario
    ***********************************/     
public static function getFirstModule($uid)
	{
	/* AUMENTADO PARA EL CONTROL DE PERMISOS POR USUARIO */
	$sql = "select top * 
			from sys_modules_users 
			left join sys_modules on (mus_mod_uid=mod_uid) 
			where mus_rol_uid='".$uid."' and mus_place='MODULE' and mus_delete=0 
			and mod_language='es' 
			order by mod_position asc 
			";

	$dbLink=new DBmysql;
	//$dbLink->connect("$basedatos", "$host", "$user", "$pass");		
	$dbLink->query($sql);
	$nroreg = $dbLink->numrows();
	$module = $dbLink->next_record();
	return $module["mod_index"];
	}    
//OBTIENE UNA IMAGEN DE LA GALERIA DE IMAGENES DE FORMA RANDOMICA
public static function getRandImg($ID,$type)
	{
	switch ($type)
		{
		case 1:
				$sql = "select gai_image 
						from mdl_gallery_images 
						where gai_delete<>1 and gai_gal_uid in (select gal_uid 
											  					from mdl_gallery 
																where gal_gac_uid=" . $ID . ") 
						ORDER BY RAND() LIMIT 1";
				break;
		case 2:
				$sql = "select gai_image 
						from mdl_gallery_images 
						where gai_gal_uid =" . $ID . " and gai_delete<>1 
						ORDER BY RAND() LIMIT 1";
				break;
		}
	$dbLink=new DBmysql;
	//$dbLink->connect("$basedatos", "$host", "$user", "$pass");		
	$dbLink->query($sql);
	$nroreg = $dbLink->numrows();
	$gallery = $dbLink->next_record();
	$gai_image = $gallery["gai_image"];
	return $gai_image;
	}

public static function getOneaName($name)
	{
	$nameLong = explode(" ",$name);
	return $nameLong[0];
	}

public static function getGalleryId($uid,$position,$type='next')
	{
	$dbg = new DBmysql();
    //$dbg->connect("$basedatos", "$host", "$user", "$pass");
	$newpos="";
	switch($type)
		{		
		case 'next':
					$sql = "select gai_position 
							from mdl_gallery_images 
							where gai_gal_uid=" . $uid . " 
								  and gai_position>" . $position . " 
								  and gai_delete<>1 
							order by gai_position asc 
							limit 1";
					$dbg->query($sql);
					$pos = $dbg->next_record();
					$newpos = $pos["gai_position"];							
					break;
		case 'back':					
					$sql = "select gai_position 
							from mdl_gallery_images 
							where gai_gal_uid=" . $uid . " 
								  and gai_position<" . $position . " 
								  and gai_delete<>1 
							order by gai_position desc 
							limit 1";
					$dbg->query($sql);
					$pos = $dbg->next_record();
					$newpos = $pos["gai_position"];							
					break;
		}
	return $newpos;
	}
public static function getUrlChild($uid)
	{
	$sql = "select * 
			from mdl_contents 
			left join mdl_contents_languages on (con_uid=col_con_uid) 
			where col_language='es' 
				  and con_parent=" . $uid . " 
				  and col_status='ACTIVE' and
				  con_delete<>1  
			order by con_position 
			limit 1 ";
			
	$dbLink=new DBmysql;
	//$dbLink->connect("$basedatos", "$host", "$user", "$pass");		
	$dbLink->query($sql);
	$nroreg = $dbLink->numrows();
	$nextuid = $dbLink->next_record();
	if ($nextuid["col_url"]=="")
		return "";
	else
		return $nextuid["col_url"] . "/" ;	
	
	}
public static function getCategoryDocs()
	{
	/* AUMENTADO PARA EL CONTROL DE PERMISOS POR USUARIO */
	$sql = "select * 
			from mdl_docs_category 
			left join mdl_docs_category_languages on (dca_uid=dcl_dca_uid) 
			where dca_delete<>1 
				  and dcl_status='ACTIVE' 
			order by dca_position 
			limit 1";
	$dbLink=new DBmysql;
	//$dbLink->connect("$basedatos", "$host", "$user", "$pass");		
	$dbLink->query($sql);
	$nroreg = $dbLink->numrows();
	$fistcontent = $dbLink->next_record();
	if ($fistcontent["dcl_url"]=="")
		return "";
	else
		return $fistcontent["dcl_url"] . "/" ;
	}	
public static function getGalleryWithImg()
	{
	/* AUMENTADO PARA EL CONTROL DE PERMISOS POR USUARIO */
	$sql = "select distinct(gai_gal_uid) 
			from mdl_gallery_images 
			where gai_delete<>1";
	$dbLink=new DBmysql;
	//$dbLink->connect("$basedatos", "$host", "$user", "$pass");		
	$dbLink->query($sql);
	$nroreg = $dbLink->numrows();
	$uids="";
	while ($fistcontent=$dbLink->next_record())
		{		
		if ($uids=="")
			$uids = $fistcontent["gai_gal_uid"];
		else
			$uids = $uids . "," . $fistcontent["gai_gal_uid"];			
		}
	return $uids;
	}	
public static function verifyBulletinNews($bul_uid,$new_uid)
	{
	$sql = "select * 
			from mdl_bulletin_news 
			where bne_bul_uid=" . $bul_uid . " 
				  and bne_new_uid=" . $new_uid;
	$dbLink=new DBmysql;
	//$dbLink->connect("$basedatos", "$host", "$user", "$pass");		
	$dbLink->query($sql);		
	$numsubs = $dbLink->numrows();
	if ($numsubs>0) return "checked";
	else return "";
	}	
	
public static function getnumDocs($dca_uid)
	{
	$sql = "select COUNT(*) as NUM from mdl_docs where doc_dca_uid=" . $dca_uid;
	global $basedatos, $host, $user,$pass ;
	$db_category = new DBmysql();
	//$db_category->connect("$basedatos", "$host", "$user", "$pass");
	$db_category->query($sql);
	$record = $db_category->next_record();
	$num = $record["NUM"];
	if ($num!=0) $num = "(" . $num . ")";
	else $num="";
	return $num;
	}	
	
public static function galleryCatVerify($gac_uid)
	{
	$sql = "select gal_uid 
			from mdl_gallery 
			where gal_gac_uid=" . $gac_uid . "
				  and gal_delete<>1 
				  and gal_status='ACTIVE'";	
	$db_category=new DBmysql;
	//$db_category->connect("$basedatos", "$host", "$user", "$pass");		
	$db_category->query($sql);		
	$numgallerys = $db_category->numrows();
	$answer="NO";
	if ($numgallerys>0)
		{
		$gal_uids = "";
		while ($category = $db_category->next_record())
			{
			if ($gal_uids=="") $gal_uids = $category["gal_uid"];
			else $gal_uids = $gal_uids . "," . $category["gal_uid"];
			}
		$sql = "select gai_uid 
				from mdl_gallery_images 
				where gai_gal_uid in (" . $gal_uids . ") 
					  and gai_delete<>1";
		$db_category->query($sql);
		$numimages = $db_category->query($sql);
		if ($numimages>0) $answer="YES";
		else $answer="NO";			
		}
	return $answer;
	}	
public static function galleryRandImg($gal_uid)
	{
	$sql = "select gai_image 
			from mdl_gallery_images 
			where gai_delete<>1 and gai_gal_uid=" . $gal_uid . " 
			ORDER BY RAND() LIMIT 1";			
	$db_images=new DBmysql;
	//$db_images->connect("$basedatos", "$host", "$user", "$pass");		
	$db_images->query($sql);		
	$numgallerys = $db_images->numrows();
	$image="";
	if ($numgallerys>0)
		{
		$imageselected = $db_images->next_record();
		$image = $imageselected["gai_image"];
		}
	return $image;
	}	
// Obtiene la direccion URL del primer foro
public static function getFirstForoUrl($lang)
	{
	$sql = "select fol_url  
			from mdl_foros 
			left join mdl_foros_languages on (for_uid=fol_for_uid) 
			where for_status='ACTIVE' 
				  and fol_language='" . $lang . "' 
				  and for_delete<>1
			order by for_position  
			limit 1";
	global $basedatos, $host, $user,$pass ;
	$db_category = new DBmysql();
	//$db_category->connect("$basedatos", "$host", "$user", "$pass");
	$db_category->query($sql);
	if($record = $db_category->next_record())
		$fol_url = $record["fol_url"] . "/";
	else
		$fol_url = "";
	return $fol_url;
	}	

public static function getFullnameUser($use_uid)
	{
	$sql = "select use_name, use_lastname 	
			from mdl_users 
			where use_uid=" . $use_uid . " 
			limit 1";

	global $basedatos, $host, $user,$pass ;
	$db_category = new DBmysql();
	//$db_category->connect("$basedatos", "$host", "$user", "$pass");
	$db_category->query($sql);
	if($record = $db_category->next_record())
		$fol_url = $record["use_name"] . " " . $record["use_lastname"];
	else
		$fol_url = "";
	return $fol_url;
	}	
/**
  Obtiene etiquetas de los modulos
*/
public static function modulesLabels($subMenuId=''){
	global $indexMenu, $indexSubMenu,$lang;
	if($subMenuId==''){
		$menuLabel = admin::getDBValue("select mod_name from sys_modules where mod_uid='".$indexSubMenu."' and mod_delete=0 and mod_language='".$lang."'");	
	}
	else{
		$sSubMenu=admin::getDbValue("select mod_uid from sys_modules where mod_alias='".$subMenuId."'");
		$menuLabel = admin::getDBValue("select mod_name from sys_modules where mod_uid='".$sSubMenu."' and mod_delete=0 and mod_language='".$lang."'");	
	}
	return($menuLabel);
}


public static function modulesLink($subMenuId=''){
	global $indexMenu, $indexSubMenu,$lang;
	if($subMenuId==''){
		$menuLabel = admin::getDBValue("select mod_index from sys_modules where mod_uid='".$indexSubMenu."' and mod_delete=0 and mod_status='ACTIVE' and mod_language='".$lang."'");	
	}
	else{
		$sSubMenu=admin::getDbValue("select mod_uid from sys_modules where mod_alias='".$subMenuId."'");
		$menuLabel = admin::getDBValue("select mod_index from sys_modules where mod_uid='".$sSubMenu."' and mod_delete=0 and mod_status='ACTIVE' and mod_language='".$lang."'");	
	}
	return($menuLabel);
}
/**
	Control de Accesos
*/
public static function control($con_uid,$lab_uid,$lab_category='label'){
 $access = admin::getDBValue("select mof_delete from sys_modules_fields where mof_lab_uid='".$lab_uid."' and mof_lab_category='".$lab_category."' and mof_mod_uid='".$con_uid."' and mof_rol_uid='".$_SESSION["usr_rol"]."'");	
 return (!$access);
}

public static function time_diff($dt1,$dt2){
    $y1 = substr($dt1,0,4);
    $m1 = substr($dt1,5,2);
    $d1 = substr($dt1,8,2);
    $h1 = substr($dt1,11,2);
    $i1 = substr($dt1,14,2);
    $s1 = substr($dt1,17,2);   

    $y2 = substr($dt2,0,4);
    $m2 = substr($dt2,5,2);
    $d2 = substr($dt2,8,2);
    $h2 = substr($dt2,11,2);
    $i2 = substr($dt2,14,2);
    $s2 = substr($dt2,17,2);   

    $r1=date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));
    $r2=date('U',mktime($h2,$i2,$s2,$m2,$d2,$y2));
    return ($r1-$r2);
}	
public static function time_sum($dt1,$dt2){
    $y1 = substr($dt1,0,4);
    $m1 = substr($dt1,5,2);
    $d1 = substr($dt1,8,2);
    $h1 = substr($dt1,11,2);
    $i1 = substr($dt1,14,2);
    $s1 = substr($dt1,17,2);   

    $y2 = substr($dt2,0,4);
    $m2 = substr($dt2,5,2);
    $d2 = substr($dt2,8,2);
    $h2 = substr($dt2,11,2);
    $i2 = substr($dt2,14,2);
    $s2 = substr($dt2,17,2);   

    $r1=date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));
    $r2=date('U',mktime($h2,$i2,$s2,$m2,$d2,$y2));
    return ($r1+$r2);
}	

public static function getRoundItem($sub_uid)
{
	$dateNow= date ("Y-m-d H:i:s");
	$lastSubUid = $this->getDbValue("select max(rou_round) from mdl_round where rou_sub_uid=$sub_uid");
	if(!isset($lastSubUid)) 
	{
		$lastSubUid=1;
		$this->getDbValue("insert into mdl_round values (null, $sub_uid, $lastSubUid,'$dateNow',0,0)");
	}
	return $lastSubUid;
	}

public static function updateSubasta()
  {
    $dbLink=new DBmysql();
    $SQL = "select sub_uid, sub_hour_end, sub_deadtime, sub_finish from mdl_subasta where sub_finish in (1,2)";
   // echo $SQL;
    $valCant=$dbLink->numrows($SQL);
    if($valCant>0){
        $dbLink->query($SQL);
        while($details=$dbLink->next_record()){
            $timetobe=admin::time_diff($details["sub_hour_end"],date('Y-m-d H:i:s'));
            $timedead=admin::time_diff($details["sub_deadtime"],date('Y-m-d H:i:s'));
            $finish=$details["sub_finish"];
            $sub_uid=$details["sub_uid"];
            //echo $timetobe." - ".$timedead. "-FINISH=".$finish;die;
            if (($timetobe>0)){
             //NADA Q ACTUALIZAR
            }
            elseif(($timedead>0))
            {
                //SUBASTANDOSE
                if($finish!=2) self::getDbValue ("update mdl_subasta set sub_finish=2 where sub_uid=".$sub_uid);
            }else {
                    //CONCLUIDA
                        self::getDbValue ("update mdl_subasta set sub_finish=3 where sub_uid=".$sub_uid);
                    }
        }


    }
    
    
}


public static function updateSubastaItem()
  {
    $dbLink=new DBmysql();
    $SQL = "select sub_uid, sub_hour_end, sub_deadtime, sub_finish from mdl_subasta where sub_modalidad='ITEM' and sub_finish in (1,2)";
   // echo $SQL;
    $valCant=$dbLink->numrows($SQL);
    if($valCant>0){
        $dbLink->query($SQL);
        while($details=$dbLink->next_record()){
            $timetobe=admin::time_diff($details["sub_hour_end"],date('Y-m-d H:i:s'));
            $timedead=admin::time_diff($details["sub_deadtime"],date('Y-m-d H:i:s'));
            $finish=$details["sub_finish"];
            $sub_uid=$details["sub_uid"];
            //echo $timetobe." - ".$timedead. "-FINISH=".$finish;die;
            if (($timetobe>0)){
             //NADA Q ACTUALIZAR
            }
            elseif(($timedead>0))
            {
                //SUBASTANDOSE
                if($finish!=2) self::getDbValue ("update mdl_subasta set sub_finish=2 where sub_uid=".$sub_uid);
                
            }else {
                    //CONCLUIDA
                        self::getDbValue ("update mdl_subasta set sub_finish=3 where sub_uid=".$sub_uid);
                    }
        }


    }
  }
public static function validaRav($uid, $rol, $tipologia, $moneda, $monto, $unidadUid, $debug=false)
{
    $rolAplica = 0;
            $sql =  "select count(*) from mdl_rav,mdl_rav_access where rav_uid=raa_rav_uid and rav_tipologia=$tipologia and rav_delete=0 and rav_rol_uid=$rol and rav_cur_uid=".$moneda." and ($monto between rav_monto_inf and rav_monto_sup) and raa_uni_uid in ($unidadUid)";
            //echo $sql;
            $valida = admin::getDbValue($sql);
            //echo $valida;
            $unidad=0;
            switch ($tipologia){
                case 1:
                    $sql =  "select count(*) from mdl_subasta_unidad where suu_sub_uid=$uid and suu_uni_uid in(".$unidadUid.")";
                    $unidad = admin::getDbValue($sql);
                    break;
                case 3:
                    $sql =  "select count(*) from mdl_solicitud_unidad where sou_sol_uid=$uid and sou_uni_uid in(".$unidadUid.")";
                    $unidad = admin::getDbValue($sql);
                    break;
                case 4:
                    $sql =  "select count(*) from mdl_orden_unidad where oru_orc_uid=$uid and oru_uni_uid in(".$unidadUid.")";
                    $unidad = admin::getDbValue($sql);
                    break;
                default:
                    $sql =  "select count(*) from mdl_subasta_unidad where suu_sub_uid=$uid and suu_uni_uid in(".$unidadUid.")";
                    $unidad = admin::getDbValue($sql);
                    break;
            }
            //echo $sql;
            if(($valida>0)&&($unidad>0))
            {   
                $montoBase = $monto;
                $sqlMonto =  "select top 1 rav_monto_inf, rav_monto_sup from mdl_rav,mdl_rav_access where rav_uid=raa_rav_uid and rav_tipologia=$tipologia and rav_delete=0 and rav_rol_uid=$rol and rav_cur_uid=".$moneda." and ($monto between rav_monto_inf and rav_monto_sup) and raa_uni_uid in ($unidadUid)";
                $dbNew = new DBmysql();
                $dbNew->query($sqlMonto);
                $montoL=$dbNew->next_record();
                $montoMenor = $montoL["rav_monto_inf"];
                $montoMayor = $montoL["rav_monto_sup"];
                //echo $monto."##".$montoMenor.">>".$montoMayor;
                self::doLog("OK- UID:". $uid." Rol:".$rol." TIPO:".$tipologia." MONEDA:".$moneda." MONTO:".$monto." UNIDAD:".$unidadUid." MONTO MENOR:".$montoMenor." MONTO MAYOR:".$montoMayor, "RAV", SAVELOG);
                if(($montoMayor!=0)&&(is_numeric($montoMayor))){
                    if(($montoBase>=$montoMenor)&&($montoBase<=$montoMayor)) {$rolAplica=1;}
                }else{if(($montoBase>=$montoMenor)&&(is_numeric($montoMenor))) {$rolAplica=1;}}                
            }else {
                   self::doLog("FAILED- UID:". $uid." Rol:".$rol." TIPO:".$tipologia." MONEDA:".$moneda." MONTO:".$monto." UNIDAD:".$unidadUid,"RAV",SAVELOG);
            }
            return $rolAplica;
}
public static function insertMail($cli_uid,$nti_uid, $attach, $cli_email, $sol_uid='NULL', $orc_uid='NULL', $nro_oc='NULL' ){
    $mailDB =new DBmysql();
    $sSQL = "   insert into mdl_notificacion_envio "
            . " (noe_cli_uid, noe_email, noe_sol_uid, noe_orc_uid,"
            . "  noe_nro_oc, noe_nti_uid, noe_attach_exist, noe_attach,"
            . "  noe_status, noe_retry, noe_fecha) "
            . " values"
            . " ($cli_uid, '$cli_email', $sol_uid, $orc_uid, $nro_oc, $nti_uid, 1, '$attach', 0, 0, GETDATE())";
    //self::doLog($sSQL);
    $mailDB->query($sSQL);    
}
public static function doLog($text, $file="ULOG", $debug=SAVELOG)
{
      // open log file
    if($debug){
      $fecha= date("Ymd");
      $filename = PATH_ROOT."/log/".$file.$fecha.".log";
      
      $fh = fopen($filename, "a") or die("Could not open log file.");
      fwrite($fh, date("d-m-Y, H:i")." - $text\n") or die("Could not write file!");
      fclose($fh);
    }
}
public static function numberFormat($number)
{
    $numero = number_format($number,2,".",",");
    return $numero;
}
public static function checkPassword($pwd, &$errors) {
    $errors_init = $errors;

    if (strlen($pwd) < 8) {
        $errors[] = "Password too short!";
    }

    if (!preg_match("#[0-9]+#", $pwd)) {
        $errors[] = "Password must include at least one number!";
    }

    if (!preg_match("#[a-zA-Z]+#", $pwd)) {
        $errors[] = "Password must include at least one letter!";
    }     

    return ($errors == $errors_init);
}
public static function generateToken( $formName ) 
{
    $secretKey = 'gsfhs154aergz2#33';
    if ( !session_id() ) {
        session_start();
    }
    $sessionId = session_id();
              
    return sha1( $formName.$sessionId.$secretKey );
 
}
public static function checkToken( $token, $formName ) 
{   
   // echo "@@".$token.":";
    $tToken=self::generateToken( $formName );
    self::doLog("Token1=".$tToken." Token2=".$token,"CHECKTOKEN", SAVELOG);
    if($token!=$tToken) return false;
    else return true;

}
public static function getIpAddress() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = self::doFilter ($_SERVER['HTTP_CLIENT_IP']);
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = self::doFilter ($_SERVER['HTTP_X_FORWARDED_FOR']);
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = self::doFilter ($_SERVER['HTTP_X_FORWARDED']);
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = self::doFilter ($_SERVER['HTTP_FORWARDED_FOR']);
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = self::doFilter ($_SERVER['HTTP_FORWARDED']);
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = self::doFilter ($_SERVER['REMOTE_ADDR']);
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}
public static function checkIP() 
{ 
  $sw=false;
  $ipRemota = self::getIpAddress();
  $lsIp = new DBmysql();
  $lsIp->query("select lis_ip from mdl_listIp where lis_delete=0");
  while($lIp=$lsIp->next_record()){
      $lista= $lIp["lis_ip"];
      self::doLog("ipRemota: ".$ipRemota." IpLista: ".$lista." REMOTE_ADDR: ".$_SERVER['REMOTE_ADDR'],"IPLOG");
  if(strstr($ipRemota, $lista)){       
      $sw=true;
      break;
    }
  }
  $flagIpCheck = IP_CHECK;
  if (!isset($flagIpCheck)) $flagIpCheck=true;
  if($flagIpCheck) {if(!$sw) { header("HTTP/1.0 403 Forbidden"); self::setSession("CHECK_IP", true);}else { self::setSession("CHECK_IP", false);}}
}
public static function lockUser()
{
    $timeLock = LOCK_TIME;
    if((!isset($timeLock))||(!is_numeric($timeLock))) $timeLock=10;
    $ipRemota = self::getIpAddress();
    $lsIp=self::getDbValue("select top 1 loc_datetime from mdl_lock where loc_estado=0 and loc_retry>=3 and loc_ipV4='".$ipRemota."' and GETDATE()<loc_datetime");
    //self::doLog("IP:".$lsIp." ipRemota=".$ipRemota);
    if($lsIp!="") {header("HTTP/1.0 403 Forbidden"); self::setSession("LOCK_IP", true); self::setSession("LOCK_TIME", $lsIp);}
    else {self::setSession("LOCK_IP", false);self::setSession("LOCK_TIME", ""); self::getDbValue("delete from mdl_lock where loc_estado=0 and loc_ipV4='".$ipRemota."' and GETDATE()>loc_datetime"); }
}

public static function unlockIp()
{
    $ipRemota = self::getIpAddress();
    self::getDbValue("delete from mdl_lock where loc_estado=0 and loc_ipV4='".$ipRemota."'"); 
}
public static function lockFailed()
{
    $timeLock = LOCK_TIME;
    if((!isset($timeLock))||(!is_numeric($timeLock))) $timeLock=10;
    $dateTime = new DateTime;
    $dateTime->modify("+{$timeLock} minutes");
    $ipRemota = self::getIpAddress();
    $lsIp=self::getDbValue("select count(*) from mdl_lock where loc_ipV4='".$ipRemota."' and loc_estado=0");
    if($lsIp>0) self::getDbValue ("update mdl_lock set loc_retry+=1, loc_datetime='". $dateTime->format ("Y-m-d H:i:s") ."'");
    else  self::getDbValue ("insert into mdl_lock (loc_ipV4, loc_datetime, loc_retry, loc_estado) values ( '".$ipRemota."','". $dateTime->format ("Y-m-d H:i:s") ."',1,0)");
}
}// LLAVE FINAL DE LA CLASE	
?>
